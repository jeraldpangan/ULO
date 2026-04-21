<?php
/**
 * Enrollment Model
 * Handles enrollment data operations
 */

require_once __DIR__ . '/../config/Connection.php';

class Enrollment {
    
    private \PDO $db;
    
    public function __construct() {
        $connection = new Connection();
        $this->db = $connection->connect();
    }
    
    /**
     * Create a new enrollment
     */
    public function create($data) {
        try {
            $query = "INSERT INTO enrollments (student_id, course_id, status) 
                      VALUES (?, ?, ?)";
            
            $stmt = $this->db->prepare($query);
            
            $result = $stmt->execute([
                $data['student_id'],
                $data['course_id'],
                'enrolled'
            ]);
            
            if ($result) {
                return $this->db->lastInsertId();
            }
            
            return false;
        } catch (Exception $e) {
            throw new Exception('Error creating enrollment: ' . $e->getMessage());
        }
    }
    
    /**
     * Find enrollment by ID
     */
    public function findById($id) {
        try {
            $query = "SELECT e.*, u.full_name as student_name, c.title as course_title, c.course_code 
                      FROM enrollments e 
                      JOIN users u ON e.student_id = u.id 
                      JOIN courses c ON e.course_id = c.id 
                      WHERE e.id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $enrollment = $stmt->fetch();
            
            return $enrollment ?: null;
        } catch (Exception $e) {
            throw new Exception('Error finding enrollment: ' . $e->getMessage());
        }
    }
    
    /**
     * Get enrollments for a student
     */
    public function getStudentEnrollments($studentId) {
        try {
            $query = "SELECT e.*, c.id as course_id, c.title as course_title, c.course_code, c.credits, c.description
                      FROM enrollments e 
                      JOIN courses c ON e.course_id = c.id 
                      WHERE e.student_id = ? AND e.status IN ('enrolled', 'completed')
                      ORDER BY e.enrolled_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error fetching student enrollments: ' . $e->getMessage());
        }
    }
    
    /**
     * Check if student is already enrolled in course
     */
    public function checkEnrollment($studentId, $courseId) {
        try {
            $query = "SELECT COUNT(*) as count FROM enrollments 
                      WHERE student_id = ? AND course_id = ? AND status IN ('enrolled', 'completed')";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$studentId, $courseId]);
            $result = $stmt->fetch();
            
            return $result['count'] > 0;
        } catch (Exception $e) {
            throw new Exception('Error checking enrollment: ' . $e->getMessage());
        }
    }
    
    /**
     * Get course capacity check
     */
    public function checkCourseCapacity($courseId) {
        try {
            $query = "SELECT c.max_capacity, COUNT(e.id) as enrolled_count 
                      FROM courses c 
                      LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'enrolled'
                      WHERE c.id = ?
                      GROUP BY c.id";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$courseId]);
            $result = $stmt->fetch();
            
            return $result;
        } catch (Exception $e) {
            throw new Exception('Error checking course capacity: ' . $e->getMessage());
        }
    }
    
    /**
     * Update enrollment
     */
    public function update($id, $data) {
        try {
            $updateFields = [];
            $updateParams = [];
            
            if (isset($data['grade'])) {
                $updateFields[] = 'grade = ?';
                $updateParams[] = $data['grade'];
            }
            
            if (isset($data['status'])) {
                $updateFields[] = 'status = ?';
                $updateParams[] = $data['status'];
                
                if ($data['status'] === 'completed') {
                    $updateFields[] = 'completed_at = CURRENT_TIMESTAMP';
                }
            }
            
            if (empty($updateFields)) {
                return true;
            }
            
            $updateFields[] = 'updated_at = CURRENT_TIMESTAMP';
            $updateParams[] = $id;
            
            $query = "UPDATE enrollments SET " . implode(', ', $updateFields) . " WHERE id = ?";
            $stmt = $this->db->prepare($query);
            
            return $stmt->execute($updateParams);
        } catch (Exception $e) {
            throw new Exception('Error updating enrollment: ' . $e->getMessage());
        }
    }
    
    /**
     * Drop enrollment
     */
    public function drop($studentId, $courseId) {
        try {
            $query = "UPDATE enrollments SET status = 'dropped', updated_at = CURRENT_TIMESTAMP 
                      WHERE student_id = ? AND course_id = ? AND status = 'enrolled'";
            
            $stmt = $this->db->prepare($query);
            
            return $stmt->execute([$studentId, $courseId]);
        } catch (Exception $e) {
            throw new Exception('Error dropping enrollment: ' . $e->getMessage());
        }
    }
    
    /**
     * Get all enrollments report (admin)
     */
    public function getAllEnrollments($limit = 100, $offset = 0) {
        try {
            $query = "SELECT e.*, u.full_name as student_name, u.email, c.title as course_title, c.course_code 
                      FROM enrollments e 
                      JOIN users u ON e.student_id = u.id 
                      JOIN courses c ON e.course_id = c.id 
                      ORDER BY e.enrolled_at DESC
                      LIMIT ? OFFSET ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$limit, $offset]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error fetching enrollments: ' . $e->getMessage());
        }
    }
    
    /**
     * Get course popularity report
     */
    public function getCoursePopularity() {
        try {
            $query = "SELECT c.id, c.course_code, c.title, c.credits, c.max_capacity,
                             COUNT(e.id) as total_enrollments,
                             SUM(CASE WHEN e.status = 'enrolled' THEN 1 ELSE 0 END) as active_enrollments,
                             SUM(CASE WHEN e.status = 'completed' THEN 1 ELSE 0 END) as completed_enrollments,
                             ROUND((COUNT(e.id) / c.max_capacity * 100), 2) as enrollment_percentage
                      FROM courses c 
                      LEFT JOIN enrollments e ON c.id = e.course_id
                      WHERE c.status = 'active'
                      GROUP BY c.id
                      ORDER BY total_enrollments DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error fetching course popularity: ' . $e->getMessage());
        }
    }
}
