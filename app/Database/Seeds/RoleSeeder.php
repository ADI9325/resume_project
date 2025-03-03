<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder {
    public function run() {
        $data = [
            [
                'name' => 'admin',
                'permissions' => json_encode(['view_cv', 'delete_cv', 'upload_cv']),
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'user',
                'permissions' => json_encode(['upload_cv']),
                'created_at' => date('Y-m-d H:i:s')
            ],
        ];
        $this->db->table('roles')->insertBatch($data);
    }
}