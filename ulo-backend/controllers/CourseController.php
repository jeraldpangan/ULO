<?php
/**
 * Course Controller
 * Handles course management
 */

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class CourseController {
    
    private $courseModel;
    
    public function __construct() {
        $this->courseModel = new Course();
    }
    
    /**
     * Create a new course (admin only)
     * POST /api/courses
     */
    public function create() {
        try {
            // Verify authentication and admin role
            $authUser = AuthMiddleware::verify();
            AuthMiddleware::isAdmin($authUser);
            
            $input = Validator::getJsonBody();
            
            // Validation
            Validator::startValidation();
            Validator::required($input['course_code'] ?? '', 'Course Code');
            Validator::required($input['title'] ?? '', 'Title');
            Validator::required($input['credits'] ?? '', 'Credits');
            Validator::integer($input['credits'] ?? '', 'Credits');
            
            if (!Validator::passed()) {
                Response::validationError(Validator::getErrors());
            }
            
            // Check if course code already exists
            if ($this->courseModel->checkCourseCodeExists($input['course_code'])) {
                Response::error('Course code already exists', 400);
            }
            
            // Create course
            $courseData = [
                'course_code' => Validator::sanitize($input['course_code']),
                'title' => Validator::sanitize($input['title']),
                'description' => Validator::sanitize($input['description'] ?? ''),
                'credits' => (int)$input['credits'],
                'max_capacity' => (int)($input['max_capacity'] ?? 50),
                'created_by' => $authUser['id']
            ];
            
            $courseId = $this->courseModel->create($courseData);
            
            if ($courseId) {
                $course = $this->courseModel->findById($courseId);
                Response::success($course, 'Course created successfully', 201);
            } else {
                Response::error('Failed to create course', 500);
            }
            
        } catch (Exception $e) {
            Response::error('Error creating course: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Get all courses
     * GET /api/courses
     */
    public function getAll() {
        try {
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
    
    /**
     * Get course by ID
     * GET /api/courses/{course_id}
     */
    public function getById($courseId) {
        try {
            $course = $this->courseModel->findById($courseId);
            
            if (!$course) {
                Response::notFound('Course not found');
            }
            
            Response::success($course, 'Course retrieved successfully');
            
        } catch (Exception $e) {
            Response::error('Error retrieving course: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Update course (admin only)
     * PUT /api/courses/{course_id}
     */
    public function update($courseId) {
        try {
            // Verify authentication and admin role
            $authUser = AuthMiddleware::verify();
            AuthMiddleware::isAdmin($authUser);
            
            $input = Validator::getJsonBody();
            
            // Check if course exists
            $course = $this->courseModel->findById($courseId);
            if (!$course) {
                Response::notFound('Course not found');
            }
            
            // Prepare update data
            $updateData = [];
            
            if (isset($input['title'])) {
                Validator::required($input['title'], 'Title');
                $updateData['title'] = Validator::sanitize($input['title']);
            }
            
            if (isset($input['description'])) {
                $updateData['description'] = Validator::sanitize($input['description']);
            }
            
            if (isset($input['credits'])) {
                Validator::integer($input['credits'], 'Credits');
                $updateData['credits'] = (int)$input['credits'];
            }
            
            if (isset($input['max_capacity'])) {
                Validator::integer($input['max_capacity'], 'Max Capacity');
                $updateData['max_capacity'] = (int)$input['max_capacity'];
            }
            
            if (isset($input['status'])) {
                if (!in_array($input['status'], ['active', 'inactive'])) {
                    Response::error('Invalid status', 400);
                }
                $updateData['status'] = $input['status'];
            }
            
            if (!Validator::passed()) {
                Response::validationError(Validator::getErrors());
            }
            
            if (empty($updateData)) {
                Response::success($course, 'No changes made');
            }
            
            // Update course
            if ($this->courseModel->update($courseId, $updateData)) {
                $updatedCourse = $this->courseModel->findById($courseId);
                Response::success($updatedCourse, 'Course updated successfully');
            } else {
                Response::error('Failed to update course', 500);
            }
            
        } catch (Exception $e) {
            Response::error('Error updating course: ' . $e->getMessage(), 500);
        }
    }
}
