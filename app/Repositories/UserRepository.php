<?php
namespace App\Repositories;

use CodeIgniter\Database\ConnectionInterface;

class UserRepository {
    protected $db;

    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }

    public function findByEmail(string $email) {
        return $this->db->table('users')
                        ->where('LOWER(email)', strtolower($email))
                        ->get()
                        ->getRow();
    }

    public function findById(int $id) {
        return $this->db->table('users')
                        ->where('id', $id)
                        ->get()
                        ->getRow();
    }

    public function create($data) {
        if (!isset($data['password']) || empty($data['password'])) {
            throw new \Exception("Password is required.");
        }
        if (!password_get_info($data['password'])['algo']) { 
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $this->db->table('users')->insert($data);
        return $this->db->insertID();
    }
}