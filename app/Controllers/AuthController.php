<?php
namespace App\Controllers;

use App\Repositories\UserRepository;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController {
    protected $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository(db_connect());
    }

    public function login() {
        if ($this->request->isAJAX()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $user = $this->userRepo->findByEmail($email);

            if ($user && password_verify($password, $user->password)) {
                session()->set(['user_id' => $user->id, 'role_id' => $user->role_id]);
                
                $redirectUrl = ($user->role_id == 1) ? '/admin/dashboard' : '/cv/upload';
                
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Login successful',
                    'redirect' => $redirectUrl
                ]);
            }
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid credentials']);
        }
        return view('auth/login');
    }

    public function register() {
        if ($this->request->isAJAX()) {
            $data = [
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'role_id' => 2 // Default user role
            ];
            $this->userRepo->create($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Registration successful']);
        }
        return view('auth/register');
    }
}