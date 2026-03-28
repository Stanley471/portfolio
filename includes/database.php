<?php
/**
 * Database Class
 * 
 * Singleton pattern database connection handler.
 * Provides PDO-based database operations with prepared statements.
 */

require_once __DIR__ . '/config.php';

class Database {
    private static ?PDO $instance = null;
    
    /**
     * Get database connection instance
     * 
     * @return PDO
     */
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET . " COLLATE utf8mb4_unicode_ci"
                ];
                
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                throw new Exception("Database connection failed. Please try again later.");
            }
        }
        
        return self::$instance;
    }
    
    /**
     * Execute a SELECT query
     * 
     * @param string $sql SQL query with placeholders
     * @param array $params Parameters for prepared statement
     * @return array Query results
     */
    public static function query(string $sql, array $params = []): array {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Execute a SELECT query for single row
     * 
     * @param string $sql SQL query with placeholders
     * @param array $params Parameters for prepared statement
     * @return array|null Query result or null
     */
    public static function queryOne(string $sql, array $params = []): ?array {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Execute INSERT query
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @return int Last insert ID
     */
    public static function insert(string $table, array $data): int {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = self::getInstance()->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        $stmt->execute();
        return (int) self::getInstance()->lastInsertId();
    }
    
    /**
     * Execute UPDATE query
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @param string $where WHERE clause
     * @param array $whereParams Parameters for WHERE clause
     * @return int Number of affected rows
     */
    public static function update(string $table, array $data, string $where, array $whereParams = []): int {
        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        $stmt = self::getInstance()->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        foreach ($whereParams as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->rowCount();
    }
    
    /**
     * Execute DELETE query
     * 
     * @param string $table Table name
     * @param string $where WHERE clause
     * @param array $params Parameters for WHERE clause
     * @return int Number of affected rows
     */
    public static function delete(string $table, string $where, array $params = []): int {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    /**
     * Begin a transaction
     */
    public static function beginTransaction(): void {
        self::getInstance()->beginTransaction();
    }
    
    /**
     * Commit a transaction
     */
    public static function commit(): void {
        self::getInstance()->commit();
    }
    
    /**
     * Rollback a transaction
     */
    public static function rollback(): void {
        self::getInstance()->rollBack();
    }
}
