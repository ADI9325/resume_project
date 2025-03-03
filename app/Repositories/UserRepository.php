<?php
namespace App\Repositories;

use CodeIgniter\Database\ConnectionInterface;

class UserRepository {
    protected $db;

    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }

    public function findByEmail($email) {
        return $this->db->table('users')->where('email', $email)->get()->getRow();
    }

    public function create($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->db->table('users')->insert($data);
        return $this->db->insertID();
    }
}