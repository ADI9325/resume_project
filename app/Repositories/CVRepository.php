<?php
namespace App\Repositories;

use CodeIgniter\Database\ConnectionInterface;

class CVRepository {
    protected $db;

    public function __construct(ConnectionInterface $db) {
        $this->db = $db;
    }

    public function save($data) {
        $this->db->table('cvs')->insert($data);
        return $this->db->insertID();
    }

    public function findById($id) {
        return $this->db->table('cvs')->where('id', $id)->get()->getRow();
    }

    public function findByUserId($userId) {
        return $this->db->table('cvs')->where('user_id', $userId)->get()->getResult();
    }

    public function findAll() {
        return $this->db->table('cvs')
            ->join('users', 'users.id = cvs.user_id')
            ->select('cvs.*, users.email as user_email')
            ->get()
            ->getResult();
    }
}