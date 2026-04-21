<?php
/**
 * Admin Controller
 * Handles admin operations
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class AdminController {
    
    private $userModel;
    private $courseModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->courseModel = new Course();
    }
    
    /**
     * Get all students
     * GET /api/admin/students
     */
    public function getAllStudents() {
        try {
            // Verify authentication and admin role
            $authUser = AuthMiddleware::verify();
            AuthMiddleware::isAdmin($authUser);
            
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            
            $students = $this->userModel->getAllUsers($limit, $offset);
            $total = $this->userModel->countUsers();
            
            Response::success([
                'students' => $students,
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
            ], 'Students retrieved successfully');
            
        } catch (Exception $e) {
            Response::error('Error retrieving students: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Get all courses
     * GET /api/admin/courses
     */
    public function getAllCourses() {
        try {
            // Verify authentication and admin role
            $authUser = AuthMiddleware::verify();
            AuthMiddleware::isAdmin($authUser);
            
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            
            $courses = $this->courseModel->getAllCourses($limit, $offset);
            $total = $this->courseModel->countCourses();
            
            Response::success([
                'courses' => $courses,
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
            ], 'Courses retrieved successfully');
            
        } catch (Exception $e) {
            Response::error('Error retrieving courses: ' . $e->getMessage(), 500);
        }
    }
}
