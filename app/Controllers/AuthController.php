<?php
namespace App\Controllers;

use App\Repositories\UserRepository;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends ResourceController {
    protected $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository(db_connect());
    }

    public function login() {
        if ($this->request->isAJAX()) {
            $validationRules = [
                'email' => 'required|valid_email',
                'password' => 'required|min_length[6]'
            ];

            if (!$this->validate($validationRules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->validator->getErrors()['email'] ?? $this->validator->getErrors()['password']
                ]);
            }

            $email = $this->request->getPost('email', FILTER_SANITIZE_EMAIL);
            $password = $this->request->getPost('password');

            $user = $this->userRepo->findByEmail($email);

            if ($user) {
                if (password_verify($password, $user->password)) {
                    session()->set([
                        'user_id' => $user->id,
                        'role_id' => $user->role_id,
                        'name' => $user->name
                    ]);

                    $redirectUrl = ($user->role_id == 1) ? '/admin/dashboard' : '/cv/upload';

                    log_message('info', 'Login successful for user: ' . $email);
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Login successful',
                        'redirect' => $redirectUrl
                    ]);
                } 
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid email or password'
            ]);
        }

        return view('auth/login');
    }

    public function register() {
        if ($this->request->isAJAX()) {
            $validationRules = [
                'name' => 'required|min_length[2]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'phone' => 'required|min_length[10]|max_length[20]',
                'password' => 'required|min_length[6]',
                'profile_image' => 'uploaded[profile_image]|max_size[profile_image,1024]|is_image[profile_image]'
            ];

            if (!$this->validate($validationRules)) {
                $errors = $this->validator->getErrors();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => reset($errors)
                ]);
            }

            // Handle file upload
            $profileImage = $this->request->getFile('profile_image');
            $imageName = null;
            if ($profileImage && $profileImage->isValid()) {
                $imageName = $profileImage->getRandomName();
                $profileImage->move(ROOTPATH . 'public/uploads/profiles', $imageName);
            }

            $data = [
                'name' => $this->request->getPost('name', FILTER_SANITIZE_STRING),
                'email' => $this->request->getPost('email', FILTER_SANITIZE_EMAIL),
                'phone' => $this->request->getPost('phone', FILTER_SANITIZE_STRING),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'profile_image' => $imageName,
                'role_id' => 2
            ];

            try {
                $this->userRepo->create($data);
                log_message('info', 'User registered successfully: ' . $data['email']);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Registration successful',
                    'redirect' => '/cv/upload'
                ]);
            } catch (\Exception $e) {
                // Delete uploaded image if registration fails
                if ($imageName && file_exists(ROOTPATH . 'public/uploads/profiles/' . $imageName)) {
                    unlink(ROOTPATH . 'public/uploads/profiles/' . $imageName);
                }
                log_message('error', 'Registration failed: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Registration failed: ' . $e->getMessage()
                ]);
            }
        }

        return view('auth/register');
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}
