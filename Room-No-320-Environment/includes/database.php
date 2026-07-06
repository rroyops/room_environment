<?php
/**
 * Room No. 320 Environment - Database Connector
 * Uses PDO for prepared statements, secure connections, and transaction safety.
 */

require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Elegant error display instead of raw traces
            die("<div style='font-family: Arial, sans-serif; padding: 30px; text-align: center; background: #FFF3F3; border: 1px solid #FFD2D2; border-radius: 8px; margin: 50px auto; max-width: 600px;'>
                    <h3 style='color: #D32F2F;'>Database Connection Failed</h3>
                    <p style='color: #555;'>Please ensure that your MySQL server is running in XAMPP, and you have imported the SQL file <strong>room320_environment.sql</strong> from the database folder.</p>
                    <p style='font-size: 13px; color: #888;'>Error detail: " . htmlspecialchars($e->getMessage()) . "</p>
                 </div>");
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Global database connection variable
$db = Database::getInstance()->getConnection();
?>
