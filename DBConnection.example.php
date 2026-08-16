<?php
// ============================================================
// COPY this file to DBConnection.php and update your settings.
// DBConnection.php is excluded from git for security.
// ============================================================

Class DBConnection {
    protected $db;

    function __construct(){
        $this->db = new mysqli('localhost', 'root', '', 'bsms_db');
        if(!$this->db){
            die('Database Connection Failed. Error: ' . $this->db->error);
        }
        $this->ensure_tables();
    }

    private function ensure_tables(){
        $this->db->query("CREATE TABLE IF NOT EXISTS `activity_log` (
            `log_id` INT(30) NOT NULL AUTO_INCREMENT,
            `user_id` INT(30) DEFAULT NULL,
            `fullname` VARCHAR(200) DEFAULT NULL,
            `action` VARCHAR(100) NOT NULL,
            `description` TEXT NOT NULL,
            `ip_address` VARCHAR(45) DEFAULT NULL,
            `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`log_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $this->db->query("CREATE TABLE IF NOT EXISTS `login_log` (
            `log_id` INT(30) NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(200) NOT NULL,
            `status` ENUM('success','failed') NOT NULL,
            `ip_address` VARCHAR(45) DEFAULT NULL,
            `user_agent` TEXT DEFAULT NULL,
            `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`log_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $col = $this->db->query("SHOW COLUMNS FROM `product_list` LIKE 'image_url'");
        if($col && $col->num_rows === 0){
            $this->db->query("ALTER TABLE `product_list` ADD COLUMN `image_url` TEXT NULL AFTER `description`");
        }
    }

    function db_connect(){ return $this->db; }
    function __destruct(){ $this->db->close(); }
}

function format_num($number = '', $decimal = ''){
    if(is_numeric($number)){
        $ex = explode('.', $number);
        $dec_len = isset($ex[1]) ? strlen($ex[1]) : 0;
        return number_format($number, !empty($decimal) ? $decimal : $dec_len);
    }
    return 'Invalid input.';
}

$db   = new DBConnection();
$conn = $db->db_connect();
