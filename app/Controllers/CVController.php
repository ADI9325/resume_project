<?php
namespace App\Controllers;

use App\Repositories\CVRepository;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;

class CVController extends ResourceController {
    protected $cvRepo;

    public function __construct() {
        $this->cvRepo = Services::cvRepository();
    }

    public function upload() {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $existingCv = $this->cvRepo->findByUserId($userId);

        if ($existingCv) {
            return redirect()->to('/cv/dashboard');
        }

        if ($this->request->isAJAX()) {
            if (!$this->request->is('post')) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
            }

            $file = $this->request->getFile('cv_file');
            if (!$file || !$file->isValid()) {
                return $this->response->setJSON(['success' => false, 'message' => 'No file uploaded']);
            }

            if ($file->getExtension() !== 'pdf' || $file->getSizeByUnit('mb') > 2) {
                return $this->response->setJSON(['success' => false, 'message' => 'Only PDF files up to 2MB allowed']);
            }

            $newName = $file->getRandomName();
            $uploadPath = FCPATH . 'uploads';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $newName);
            log_message('debug', 'File uploaded to: ' . $uploadPath . '/' . $newName);

            $data = [
                'user_id' => $userId,
                'file_name' => $newName,
            ];
            $cvId = $this->cvRepo->save($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'CV uploaded successfully',
                'cv_id' => $cvId,
                'redirect' => '/cv/dashboard'
            ]);
        }
        return view('cv/upload');
    }

    public function dashboard() {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $cv = $this->cvRepo->findByUserId($userId);

        return view('cv/dashboard', [
            'cv' => $cv ? $cv[0] : null,
            'baseUrl' => base_url()
        ]);
    }

    public function adminDashboard() {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $roleId = (int)session()->get('role_id');
        if ($roleId !== 1) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $cvs = $this->cvRepo->findAll();
        return view('dashboard/admin_dashboard', ['cvs' => $cvs, 'baseUrl' => base_url()]);
    }

    public function protectedView($id) {
        log_message('debug', 'protectedView called for CV ID: ' . $id);
        log_message('debug', 'Session user_id: ' . (session()->get('user_id') ?? 'Not set'));

        if (!session()->get('user_id')) {
            log_message('error', 'No user_id in session, redirecting to login');
            return redirect()->to('/auth/login');
        }

        $cv = $this->cvRepo->findById($id);
        if (!$cv) {
            return $this->response->setStatusCode(404)->setBody("CV not found.");
        }

        $userId = session()->get('user_id');
        $roleId = (int)session()->get('role_id');
        $isAdmin = $roleId === 1;

        log_message('debug', 'User ID: ' . $userId . ', Role ID: ' . $roleId . ', Is Admin: ' . ($isAdmin ? 'Yes' : 'No'));

        if ($cv->user_id !== $userId && !$isAdmin) {
            log_message('error', 'Unauthorized access for user_id: ' . $userId . ' to CV ID: ' . $id);
            return $this->response->setStatusCode(403)->setBody("Unauthorized access.");
        }

        $filePath = FCPATH . 'uploads/' . $cv->file_name;
        log_message('debug', 'Attempting to access file at: ' . $filePath);
        if (!file_exists($filePath)) {
            log_message('error', 'File not found at: ' . $filePath);
            return $this->response->setStatusCode(404)->setBody("File not found at: " . $filePath);
        }

        $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $cv->file_name . '"')
            ->setHeader('X-Download-Options', 'noopen')
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0')
            ->setHeader('X-Frame-Options', 'SAMEORIGIN')
            ->setHeader('Access-Control-Allow-Origin', 'http://localhost:8080') 
            ->setHeader('Content-Security-Policy', "default-src 'self'; object-src 'self'; script-src 'self' https://cdnjs.cloudflare.com;")
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Content-Length', filesize($filePath))
            ->setHeader('Accept-Ranges', 'none');

        return $this->response->setBody(file_get_contents($filePath));
    }
}