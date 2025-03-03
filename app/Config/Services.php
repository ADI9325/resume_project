<?php
namespace Config;

use CodeIgniter\Config\BaseService;
use App\Repositories\CVRepository;
use CodeIgniter\Database\ConnectionInterface;

class Services extends BaseService {
    public static function cvRepository($getShared = true) {
        return new CVRepository(\Config\Database::connect()); // Use \Config\Database::connect() for the default connection
    }
}