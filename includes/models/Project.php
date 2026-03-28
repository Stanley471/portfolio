<?php
/**
 * Project Model
 * 
 * Handles all database operations for projects.
 */

require_once __DIR__ . '/../database.php';

class Project {
    
    /**
     * Get all projects
     * 
     * @param bool $activeOnly Only get active projects
     * @param int|null $limit Limit number of results
     * @return array Projects array
     */
    public static function getAll(bool $activeOnly = true, ?int $limit = null): array {
        $sql = "SELECT * FROM projects";
        
        if ($activeOnly) {
            $sql .= " WHERE status = 'active'";
        }
        
        $sql .= " ORDER BY is_featured DESC, created_at DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT {$limit}";
        }
        
        return Database::query($sql);
    }
    
    /**
     * Get featured projects
     * 
     * @param int $limit Number of projects to get
     * @return array Featured projects
     */
    public static function getFeatured(int $limit = 5): array {
        return Database::query(
            "SELECT * FROM projects WHERE status = 'active' AND is_featured = 1 ORDER BY created_at DESC LIMIT {$limit}"
        );
    }
    
    /**
     * Get project by ID
     * 
     * @param int $id Project ID
     * @return array|null Project data or null
     */
    public static function getById(int $id): ?array {
        return Database::queryOne("SELECT * FROM projects WHERE id = :id", [':id' => $id]);
    }
    
    /**
     * Get project by slug
     * 
     * @param string $slug Project slug
     * @return array|null Project data or null
     */
    public static function getBySlug(string $slug): ?array {
        return Database::queryOne(
            "SELECT * FROM projects WHERE slug = :slug AND status = 'active'",
            [':slug' => $slug]
        );
    }
    
    /**
     * Create new project
     * 
     * @param array $data Project data
     * @return int New project ID
     */
    public static function create(array $data): int {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = createSlug($data['title']);
        }
        
        // Check for duplicate slug
        $existing = self::getBySlug($data['slug']);
        if ($existing) {
            $data['slug'] .= '-' . time();
        }
        
        return Database::insert('projects', $data);
    }
    
    /**
     * Update project
     * 
     * @param int $id Project ID
     * @param array $data Updated data
     * @return bool True if updated
     */
    public static function update(int $id, array $data): bool {
        // Update slug if title changed and slug not explicitly set
        if (isset($data['title']) && !isset($data['slug'])) {
            $data['slug'] = createSlug($data['title']);
        }
        
        $affected = Database::update(
            'projects',
            $data,
            'id = :id',
            [':id' => $id]
        );
        
        return $affected > 0;
    }
    
    /**
     * Delete project
     * 
     * @param int $id Project ID
     * @return bool True if deleted
     */
    public static function delete(int $id): bool {
        $project = self::getById($id);
        if ($project && $project['image']) {
            deleteUpload($project['image']);
        }
        
        $affected = Database::delete('projects', 'id = :id', [':id' => $id]);
        return $affected > 0;
    }
    
    /**
     * Get projects by category
     * 
     * @param string $category Category name
     * @return array Projects in category
     */
    public static function getByCategory(string $category): array {
        return Database::query(
            "SELECT * FROM projects WHERE category = :category AND status = 'active' ORDER BY created_at DESC",
            [':category' => $category]
        );
    }
    
    /**
     * Get all categories
     * 
     * @return array Array of categories
     */
    public static function getCategories(): array {
        $results = Database::query(
            "SELECT DISTINCT category FROM projects WHERE status = 'active' ORDER BY category"
        );
        return array_column($results, 'category');
    }
    
    /**
     * Count total projects
     * 
     * @param bool $activeOnly Only count active
     * @return int Project count
     */
    public static function count(bool $activeOnly = true): int {
        $sql = "SELECT COUNT(*) as count FROM projects";
        if ($activeOnly) {
            $sql .= " WHERE status = 'active'";
        }
        $result = Database::queryOne($sql);
        return (int) ($result['count'] ?? 0);
    }
    
    /**
     * Search projects
     * 
     * @param string $query Search query
     * @return array Matching projects
     */
    public static function search(string $query): array {
        $search = "%{$query}%";
        return Database::query(
            "SELECT * FROM projects WHERE status = 'active' AND 
             (title LIKE :query OR short_description LIKE :query OR category LIKE :query OR tech_stack LIKE :query)
             ORDER BY created_at DESC",
            [':query' => $search]
        );
    }
    
    /**
     * Toggle featured status
     * 
     * @param int $id Project ID
     * @return bool True if toggled
     */
    public static function toggleFeatured(int $id): bool {
        $project = self::getById($id);
        if (!$project) {
            return false;
        }
        
        $newStatus = $project['is_featured'] ? 0 : 1;
        return self::update($id, ['is_featured' => $newStatus]);
    }
    
    /**
     * Toggle active status
     * 
     * @param int $id Project ID
     * @return bool True if toggled
     */
    public static function toggleStatus(int $id): bool {
        $project = self::getById($id);
        if (!$project) {
            return false;
        }
        
        $newStatus = $project['status'] === 'active' ? 'inactive' : 'active';
        return self::update($id, ['status' => $newStatus]);
    }
}
