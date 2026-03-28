<?php
/**
 * Skill Model
 * 
 * Handles all database operations for skills.
 */

require_once __DIR__ . '/../database.php';

class Skill {
    
    /**
     * Get all skills
     * 
     * @param bool $activeOnly Only get active skills
     * @return array Skills array
     */
    public static function getAll(bool $activeOnly = true): array {
        $sql = "SELECT * FROM skills";
        
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        
        $sql .= " ORDER BY display_order ASC, name ASC";
        
        return Database::query($sql);
    }
    
    /**
     * Get skill by ID
     * 
     * @param int $id Skill ID
     * @return array|null Skill data or null
     */
    public static function getById(int $id): ?array {
        return Database::queryOne("SELECT * FROM skills WHERE id = :id", [':id' => $id]);
    }
    
    /**
     * Get skill by name
     * 
     * @param string $name Skill name
     * @return array|null Skill data or null
     */
    public static function getByName(string $name): ?array {
        return Database::queryOne(
            "SELECT * FROM skills WHERE name = :name",
            [':name' => $name]
        );
    }
    
    /**
     * Create new skill
     * 
     * @param array $data Skill data
     * @return int New skill ID
     */
    public static function create(array $data): int {
        return Database::insert('skills', $data);
    }
    
    /**
     * Update skill
     * 
     * @param int $id Skill ID
     * @param array $data Updated data
     * @return bool True if updated
     */
    public static function update(int $id, array $data): bool {
        $affected = Database::update(
            'skills',
            $data,
            'id = :id',
            [':id' => $id]
        );
        
        return $affected > 0;
    }
    
    /**
     * Delete skill
     * 
     * @param int $id Skill ID
     * @return bool True if deleted
     */
    public static function delete(int $id): bool {
        $affected = Database::delete('skills', 'id = :id', [':id' => $id]);
        return $affected > 0;
    }
    
    /**
     * Get skills by category
     * 
     * @param string $category Category name
     * @return array Skills in category
     */
    public static function getByCategory(string $category): array {
        return Database::query(
            "SELECT * FROM skills WHERE category = :category AND is_active = 1 ORDER BY display_order ASC",
            [':category' => $category]
        );
    }
    
    /**
     * Get all categories
     * 
     * @return array Array of categories with skills
     */
    public static function getCategories(): array {
        $skills = self::getAll(true);
        $categories = [];
        
        foreach ($skills as $skill) {
            $cat = $skill['category'];
            if (!isset($categories[$cat])) {
                $categories[$cat] = [];
            }
            $categories[$cat][] = $skill;
        }
        
        return $categories;
    }
    
    /**
     * Count total skills
     * 
     * @param bool $activeOnly Only count active
     * @return int Skill count
     */
    public static function count(bool $activeOnly = true): int {
        $sql = "SELECT COUNT(*) as count FROM skills";
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        $result = Database::queryOne($sql);
        return (int) ($result['count'] ?? 0);
    }
    
    /**
     * Toggle active status
     * 
     * @param int $id Skill ID
     * @return bool True if toggled
     */
    public static function toggleActive(int $id): bool {
        $skill = self::getById($id);
        if (!$skill) {
            return false;
        }
        
        $newStatus = $skill['is_active'] ? 0 : 1;
        return self::update($id, ['is_active' => $newStatus]);
    }
    
    /**
     * Update display order
     * 
     * @param int $id Skill ID
     * @param int $order New order value
     * @return bool True if updated
     */
    public static function updateOrder(int $id, int $order): bool {
        return self::update($id, ['display_order' => $order]);
    }
}
