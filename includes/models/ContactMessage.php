<?php
/**
 * ContactMessage Model
 * 
 * Handles all database operations for contact messages.
 */

require_once __DIR__ . '/../database.php';

class ContactMessage {
    
    const STATUS_NEW = 'new';
    const STATUS_READ = 'read';
    const STATUS_REPLIED = 'replied';
    const STATUS_ARCHIVED = 'archived';
    
    /**
     * Get all messages
     * 
     * @param string|null $status Filter by status
     * @param int|null $limit Limit number of results
     * @return array Messages array
     */
    public static function getAll(?string $status = null, ?int $limit = null): array {
        $sql = "SELECT * FROM contact_messages";
        $params = [];
        
        if ($status !== null) {
            $sql .= " WHERE status = :status";
            $params[':status'] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT {$limit}";
        }
        
        return Database::query($sql, $params);
    }
    
    /**
     * Get message by ID
     * 
     * @param int $id Message ID
     * @return array|null Message data or null
     */
    public static function getById(int $id): ?array {
        return Database::queryOne(
            "SELECT * FROM contact_messages WHERE id = :id",
            [':id' => $id]
        );
    }
    
    /**
     * Create new message
     * 
     * @param array $data Message data
     * @return int New message ID
     */
    public static function create(array $data): int {
        // Add IP address if not set
        if (!isset($data['ip_address'])) {
            $data['ip_address'] = getClientIp();
        }
        
        return Database::insert('contact_messages', $data);
    }
    
    /**
     * Update message
     * 
     * @param int $id Message ID
     * @param array $data Updated data
     * @return bool True if updated
     */
    public static function update(int $id, array $data): bool {
        $affected = Database::update(
            'contact_messages',
            $data,
            'id = :id',
            [':id' => $id]
        );
        
        return $affected > 0;
    }
    
    /**
     * Delete message
     * 
     * @param int $id Message ID
     * @return bool True if deleted
     */
    public static function delete(int $id): bool {
        $affected = Database::delete(
            'contact_messages',
            'id = :id',
            [':id' => $id]
        );
        
        return $affected > 0;
    }
    
    /**
     * Mark message as read
     * 
     * @param int $id Message ID
     * @return bool True if updated
     */
    public static function markAsRead(int $id): bool {
        return self::update($id, ['status' => self::STATUS_READ]);
    }
    
    /**
     * Mark message as replied
     * 
     * @param int $id Message ID
     * @return bool True if updated
     */
    public static function markAsReplied(int $id): bool {
        return self::update($id, ['status' => self::STATUS_REPLIED]);
    }
    
    /**
     * Archive message
     * 
     * @param int $id Message ID
     * @return bool True if updated
     */
    public static function archive(int $id): bool {
        return self::update($id, ['status' => self::STATUS_ARCHIVED]);
    }
    
    /**
     * Count messages by status
     * 
     * @param string|null $status Status to count
     * @return int Message count
     */
    public static function count(?string $status = null): int {
        $sql = "SELECT COUNT(*) as count FROM contact_messages";
        $params = [];
        
        if ($status !== null) {
            $sql .= " WHERE status = :status";
            $params[':status'] = $status;
        }
        
        $result = Database::queryOne($sql, $params);
        return (int) ($result['count'] ?? 0);
    }
    
    /**
     * Count unread messages
     * 
     * @return int Unread count
     */
    public static function countUnread(): int {
        return self::count(self::STATUS_NEW);
    }
    
    /**
     * Get recent messages
     * 
     * @param int $limit Number of messages
     * @return array Recent messages
     */
    public static function getRecent(int $limit = 5): array {
        return self::getAll(null, $limit);
    }
    
    /**
     * Search messages
     * 
     * @param string $query Search query
     * @return array Matching messages
     */
    public static function search(string $query): array {
        $search = "%{$query}%";
        return Database::query(
            "SELECT * FROM contact_messages WHERE 
             (name LIKE :query OR email LIKE :query OR subject LIKE :query OR message LIKE :query)
             ORDER BY created_at DESC",
            [':query' => $search]
        );
    }
    
    /**
     * Check if email has sent message recently (rate limiting)
     * 
     * @param string $email Email address
     * @param int $minutes Time window in minutes
     * @return bool True if can send
     */
    public static function canSend(string $email, int $minutes = 5): bool {
        $result = Database::queryOne(
            "SELECT id FROM contact_messages 
             WHERE email = :email AND created_at > DATE_SUB(NOW(), INTERVAL {$minutes} MINUTE)
             LIMIT 1",
            [':email' => $email]
        );
        
        return $result === null;
    }
}
