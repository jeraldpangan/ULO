<?php
/**
 * Course Model
 * Handles course data operations
 */

require_once __DIR__ . '/../config/Connection.php';

class Course {
    
    private \PDO $db;
    
    public function __construct() {
        $connection = new Connection();
        $this->db = $connection->connect();
    }
    
    /**
     * Create a new course
     */
    public function create($data) {
        try {
            $query = "INSERT INTO courses (course_code, title, description, credits, max_capacity, created_by, status) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($query);
            
            $result = $stmt->execute([
                $data['course_code'],
                $data['title'],
                $data['description'] ?? null,
                $data['credits'],
                $data['max_capacity'] ?? 50,
                $data['created_by'],
                'active'
            ]);
            
            if ($result) {
                return $this->db->lastInsertId();
            }
            
            return false;
        } catch (Exception $e) {
            throw new Exception('Error creating course: ' . $e->getMessage());
        }
    }
    
    /**
     * Find course by ID
     */
    public function findById($id) {
        try {
            $query = "SELECT c.*, u.full_name as created_by_name, COUNT(e.id) as enrolled_count 
                      FROM courses c 
                      LEFT JOIN users u ON c.created_by = u.id 
                      LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'enrolled'
                      WHERE c.id = ? 
                      GROUP BY c.id";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $course = $stmt->fetch();
            
            return $course ?: null;
        } catch (Exception $e) {
            throw new Exception('Error finding course: ' . $e->getMessage());
        }
    }
    
    /**
     * Get all courses with filtering
     */
    public function getAllCourses($limit = 50, $offset = 0) {
        try {
            $query = "SELECT c.*, u.full_name as created_by_name, COUNT(e.id) as enrolled_count 
                      FROM courses c 
                      LEFT JOIN users u ON c.created_by = u.id 
                      LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'enrolled'
                      WHERE c.status = 'active'
                      GROUP BY c.id
                      ORDER BY c.created_at DESC
                      LIMIT ? OFFSET ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$limit, $offset]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error fetching courses: ' . $e->getMessage());
        }
    }
    
    /**
     * Update course
     */
    public function update($id, $data) {
        try {
            $updateFields = [];
            $updateParams = [];
            
            if (isset($data['title'])) {
                $updateFields[] = 'title = ?';
                $updateParams[] = $data['title'];
            }
            
            if (isset($data['description'])) {
                $updateFields[] = 'description = ?';
                $updateParams[] = $data['description'];
            }
            
            if (isset($data['credits'])) {
                $updateFields[] = 'credits = ?';
                $updateParams[] = $data['credits'];
            }
            
            if (isset($data['max_capacity'])) {
                $updateFields[] = 'max_capacity = ?';
                $updateParams[] = $data['max_capacity'];
            }
            
            if (isset($data['status'])) {
                $updateFields[] = 'status = ?';
                $updateParams[] = $data['status'];
            }
            
            if (empty($updateFields)) {
                return true;
            }
            
            $updateFields[] = 'updated_at = CURRENT_TIMESTAMP';
            $updateParams[] = $id;
            
            $query = "UPDATE courses SET " . implode(', ', $updateFields) . " WHERE id = ?";
            $stmt = $this->db->prepare($query);
            
            return $stmt->execute($updateParams);
        } catch (Exception $e) {
            throw new Exception('Error updating course: ' . $e->getMessage());
        }
    }
    
    /**
     * Count total courses
     */
    public function countCourses() {
        try {
            $query = "SELECT COUNT(*) as total FROM courses WHERE status = 'active'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            
            return $result['total'];
        } catch (Exception $e) {
            throw new Exception('Error counting courses: ' . $e->getMessage());
        }
    }
    
    /**
     * Check if course code exists
     */
    public function checkCourseCodeExists($courseCode, $excludeId = null) {
        try {
            $query = "SELECT COUNT(*) as count FROM courses WHERE course_code = ?";
            $params = [$courseCode];
            
            if ($excludeId) {
                $query .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch();
            
            return $result['count'] > 0;
        } catch (Exception $e) {
            throw new Exception('Error checking course code: ' . $e->getMessage());
        }
    }
}
