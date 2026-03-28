<?php
/**
 * Admin Model
 * 
 * Handles all database operations for admin users.
 */

require_once __DIR__ . '/../database.php';

class Admin {
    
    /**
     * Get admin by ID
     * 
     * @param int $id Admin ID
     * @return array|null Admin data or null
     */
    public static function getById(int $id): ?array {
        return Database::queryOne(
            "SELECT id, username, email, full_name, last_login, is_active, created_at FROM admin_users WHERE id = :id",
            [':id' => $id]
        );
    }
    
    /**
     * Get admin by username
     * 
     * @param string $username Username
     * @return array|null Admin data or null
     */
    public static function getByUsername(string $username): ?array {
        return Database::queryOne(
            "SELECT * FROM admin_users WHERE username = :username AND is_active = 1",
            [':username' => $username]
        );
    }
    
    /**
     * Get admin by email
     * 
     * @param string $email Email
     * @return array|null Admin data or null
     */
    public static function getByEmail(string $email): ?array {
        return Database::queryOne(
            "SELECT * FROM admin_users WHERE email = :email AND is_active = 1",
            [':email' => $email]
        );
    }
    
    /**
     * Get all admin users
     * 
     * @return array Admin users
     */
    public static function getAll(): array {
        return Database::query(
            "SELECT id, username, email, full_name, last_login, is_active, created_at FROM admin_users ORDER BY created_at"
        );
    }
    
    /**
     * Create new admin
     * 
     * @param array $data Admin data
     * @return int New admin ID
     */
    public static function create(array $data): int {
        // Hash password if not already hashed
        if (isset($data['password']) && !password_get_info($data['password'])['algo']) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => HASH_COST]);
        }
        
        return Database::insert('admin_users', $data);
    }
    
    /**
     * Update admin
     * 
     * @param int $id Admin ID
     * @param array $data Updated data
     * @return bool True if updated
     */
    public static function update(int $id, array $data): bool {
        // Hash password if provided
        if (isset($data['password']) && !password_get_info($data['password'])['algo']) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => HASH_COST]);
        }
        
        $affected = Database::update(
            'admin_users',
            $data,
            'id = :id',
            [':id' => $id]
        );
        
        return $affected > 0;
    }
    
    /**
     * Delete admin
     * 
     * @param int $id Admin ID
     * @return bool True if deleted
     */
    public static function delete(int $id): bool {
        $affected = Database::delete(
            'admin_users',
            'id = :id',
            [':id' => $id]
        );
        
        return $affected > 0;
    }
    
    /**
     * Verify login credentials
     * 
     * @param string $username Username
     * @param string $password Plain text password
     * @return array|false Admin data if successful, false otherwise
     */
    public static function verifyLogin(string $username, string $password): array|false {
        $admin = self::getByUsername($username);
        
        if (!$admin) {
            return false;
        }
        
        if (password_verify($password, $admin['password'])) {
            // Rehash password if necessary
            if (password_needs_rehash($admin['password'], PASSWORD_BCRYPT, ['cost' => HASH_COST])) {
                self::update($admin['id'], ['password' => $password]);
            }
            
            return $admin;
        }
        
        return false;
    }
    
    /**
     * Update last login time
     * 
     * @param int $id Admin ID
     * @return bool True if updated
     */
    public static function updateLastLogin(int $id): bool {
        return self::update($id, ['last_login' => date('Y-m-d H:i:s')]);
    }
    
    /**
     * Toggle active status
     * 
     * @param int $id Admin ID
     * @return bool True if toggled
     */
    public static function toggleActive(int $id): bool {
        $admin = self::getById($id);
        if (!$admin) {
            return false;
        }
        
        $newStatus = $admin['is_active'] ? 0 : 1;
        return self::update($id, ['is_active' => $newStatus]);
    }
    
    /**
     * Change password
     * 
     * @param int $id Admin ID
     * @param string $newPassword New plain text password
     * @return bool True if changed
     */
    public static function changePassword(int $id, string $newPassword): bool {
        return self::update($id, ['password' => $newPassword]);
    }
    
    /**
     * Check if username exists
     * 
     * @param string $username Username
     * @param int|null $excludeId Exclude this ID from check
     * @return bool True if exists
     */
    public static function usernameExists(string $username, ?int $excludeId = null): bool {
        $sql = "SELECT id FROM admin_users WHERE username = :username";
        $params = [':username' => $username];
        
        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }
        
        $result = Database::queryOne($sql, $params);
        return $result !== null;
    }
    
    /**
     * Check if email exists
     * 
     * @param string $email Email
     * @param int|null $excludeId Exclude this ID from check
     * @return bool True if exists
     */
    public static function emailExists(string $email, ?int $excludeId = null): bool {
        $sql = "SELECT id FROM admin_users WHERE email = :email";
        $params = [':email' => $email];
        
        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }
        
        $result = Database::queryOne($sql, $params);
        return $result !== null;
    }
    
    /**
     * Count total admins
     * 
     * @return int Admin count
     */
    public static function count(): int {
        $result = Database::queryOne("SELECT COUNT(*) as count FROM admin_users");
        return (int) ($result['count'] ?? 0);
    }
    
    /**
     * Login admin user
     * 
     * @param array $admin Admin data
     */
    public static function login(array $admin): void {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_full_name'] = $admin['full_name'] ?? $admin['username'];
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_login_time'] = time();
        
        // Update last login
        self::updateLastLogin($admin['id']);
    }
    
    /**
     * Logout admin user
     */
    public static function logout(): void {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_email']);
        unset($_SESSION['admin_full_name']);
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['admin_login_time']);
        
        // Regenerate session ID for security
        session_regenerate_id(true);
    }
}
