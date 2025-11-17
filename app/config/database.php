<?php
/**
 * Business Management Database System
 * Database Configuration Class
 *
 * This class handles MySQL database connections and provides
 * a secure interface for database operations.
 *
 * Features:
 * - MySQLi connection with error handling
 * - Prepared statement support
 * - Input sanitization
 * - Connection management
 */

class Database {
    private $host = 'localhost';
    private $username = 'business_db_user';
    private $password = 'secure_password';
    private $database = 'business_management';
    private $conn;

    /**
     * Establish database connection
     * @return mysqli Database connection object
     */
    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Set charset to UTF-8
        $this->conn->set_charset("utf8mb4");

        return $this->conn;
    }

    /**
     * Execute a SQL query
     * @param string $sql SQL query string
     * @return mysqli_result|bool Query result
     */
    public function query($sql) {
        return $this->conn->query($sql);
    }

    /**
     * Prepare a SQL statement
     * @param string $sql SQL statement string
     * @return mysqli_stmt Prepared statement object
     */
    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    /**
     * Escape string to prevent SQL injection
     * @param string $string String to escape
     * @return string Escaped string
     */
    public function escape($string) {
        return $this->conn->real_escape_string($string);
    }

    /**
     * Get the last insert ID
     * @return int Last inserted auto-increment ID
     */
    public function getLastInsertId() {
        return $this->conn->insert_id;
    }

    /**
     * Get connection error message
     * @return string Error message
     */
    public function getError() {
        return $this->conn->error;
    }

    /**
     * Close database connection
     */
    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    /**
     * Begin transaction
     */
    public function beginTransaction() {
        $this->conn->begin_transaction();
    }

    /**
     * Commit transaction
     */
    public function commit() {
        $this->conn->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback() {
        $this->conn->rollback();
    }
}
?>