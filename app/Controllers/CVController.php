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
        if ($this->request->isAJAX()) {
            if (!$this->request->is('post')) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
            }

            if (!session()->get('user_id')) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please log in']);
            }

            $file = $this->request->getFile('cv_file');
            if (!$file || !$file->isValid()) {
                return $this->response->setJSON(['success' => false, 'message' => 'No file uploaded']);
            }

            if ($file->getExtension() !== 'pdf' || $file->getSizeByUnit('mb') > 2) {
                return $this->response->setJSON(['success' => false, 'message' => 'Only PDF files up to 2MB allowed']);
            }

            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $newName);

            $data = [
                'user_id' => session()->get('user_id'),
                'file_name' => $newName,
            ];
            $cvId = $this->cvRepo->save($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'CV uploaded successfully',
                'cv_id' => $cvId
            ]);
        } else {
            if (!session()->get('user_id')) {
                return redirect()->to('/auth/login');
            }
            return view('cv/upload');
        }
    }

    public function adminDashboard() {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $roleId = (int)session()->get('role_id');
        log_message('debug', 'Current role_id (integer): ' . $roleId);

        if ($roleId !== 1) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $cvs = $this->cvRepo->findAll();
        return view('dashboard/admin_dashboard', ['cvs' => $cvs]);
    }

    // CVs functions

    public function view($cvId = null) {
        if (!$cvId) {
            return redirect()->to('/dashboard');
        }

        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $cv = $this->cvRepo->findById($cvId);
        if (!$cv) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('CV not found');
        }

        $userId = session()->get('user_id');
        $roleId = (int)session()->get('role_id'); 
        $isAdmin = $roleId == 1;

        if ($cv->user_id !== $userId && !$isAdmin) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Unauthorized access']);
        }

        $filePath = FCPATH . 'uploads/' . $cv->file_name;
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline');
        header('X-Content-Type-Options: nosniff');
        header("Content-Security-Policy: default-src 'self'; object-src 'none'; frame-ancestors 'self'; base-uri 'self'; form-action 'none'");
        header('X-Frame-Options: SAMEORIGIN');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: -1');
        header('X-Download-Options: noopen');
        header('Content-Length: ' . filesize($filePath));
        header('Accept-Ranges: none');

        readfile($filePath);
        exit;
    }

    public function protectedView($id)
    {
        $cv = $this->cvModel->find($id);
        
        if (!$cv) {
            return $this->response->setStatusCode(404)->setBody("File not found.");
        }
    
        $filePath = WRITEPATH . 'uploads/' . $cv->file_name;
    
        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setBody("File not found.");
        }
    
        
        // Set secure headers to prevent downloads
        $this->response->setHeader('Content-Type', 'application/pdf');
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . $cv->file_name . '"');
        $this->response->setHeader('X-Download-Options', 'noopen'); // Prevent direct download
        $this->response->setHeader('Content-Security-Policy', "script-src 'none'; object-src 'none';"); // Restrict object embedding
        $this->response->setHeader('Feature-Policy', "fullscreen 'none'; print 'none'"); // Prevent fullscreen & print
        
        return $this->response->setBody(file_get_contents($filePath));
    }
    

    public function listCvs() {
        if (!session()->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $cvs = $this->cvRepo->findByUserId($userId);

        return view('cv/list', ['cvs' => $cvs]);
    }

    
}