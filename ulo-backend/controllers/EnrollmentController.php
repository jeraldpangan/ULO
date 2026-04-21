<?php
/**
 * Enrollment Controller
 * Handles course enrollments
 */

require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class EnrollmentController {
    
    private $enrollmentModel;
    private $courseModel;
    
    public function __construct() {
        $this->enrollmentModel = new Enrollment();
        $this->courseModel = new Course();
    }
    
    /**
     * Create new enrollment
     * POST /api/enrollments
     */
    public function create() {
        try {
            // Verify authentication
            $authUser = AuthMiddleware::verify();
            
            $input = Validator::getJsonBody();
            
            // Validation
            Validator::startValidation();
            Validator::required($input['course_id'] ?? '', 'Course ID');
            Validator::integer($input['course_id'] ?? '', 'Course ID');
            
            if (!Validator::passed()) {
                Response::validationError(Validator::getErrors());
            }
            
            $courseId = (int)$input['course_id'];
            
            // Check if course exists
            $course = $this->courseModel->findById($courseId);
            if (!$course) {
                Response::notFound('Course not found');
            }
            
            // Check if already enrolled
            if ($this->enrollmentModel->checkEnrollment($authUser['id'], $courseId)) {
                Response::error('Already enrolled in this course', 400);
            }
            
            // Check course capacity
            $capacity = $this->enrollmentModel->checkCourseCapacity($courseId);
            if ($capacity['enrolled_count'] >= $capacity['max_capacity']) {
                Response::error('Course is at maximum capacity', 400);
            }
            
            // Create enrollment
            $enrollmentData = [
                'student_id' => $authUser['id'],
                'course_id' => $courseId
            ];
            
            $enrollmentId = $this->enrollmentModel->create($enrollmentData);
            
            if ($enrollmentId) {
                $enrollment = $this->enrollmentModel->findById($enrollmentId);
                Response::success($enrollment, 'Enrolled successfully', 201);
            } else {
                Response::error('Failed to create enrollment', 500);
            }
            
        } catch (Exception $e) {
            Response::error('Error creating enrollment: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Get student enrollments
     * GET /api/enrollments/student/{student_id}
     */
    public function getStudentEnrollments($studentId) {
        try {
            // Verify authentication
            $authUser = AuthMiddleware::verify();
            
            // Students can only see their own enrollments, admins can see any
            if ($authUser['role'] !== 'admin' && $authUser['id'] != $studentId) {
                Response::forbidden('Cannot access other student enrollments');
            }
            
            $enrollments = $this->enrollmentModel->getStudentEnrollments($studentId);
            
            Response::success([
                'student_id' => $studentId,
                'enrollments' => $enrollments,
                'total' => count($enrollments)
            ], 'Enrollments retrieved successfully');
            
        } catch (Exception $e) {
            Response::error('Error retrieving enrollments: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Drop course enrollment
     * DELETE /api/enrollments/{enrollment_id}
     */
    public function drop($studentId, $courseId) {
        try {
            // Verify authentication
            $authUser = AuthMiddleware::verify();
            
            // Students can only drop their own enrollments, admins can drop any
            if ($authUser['role'] !== 'admin' && $authUser['id'] != $studentId) {
                Response::forbidden('Cannot drop other student enrollments');
            }
            
            // Check if enrollment exists
            if (!$this->enrollmentModel->checkEnrollment($studentId, $courseId)) {
                Response::notFound('Enrollment not found');
            }
            
            // Drop enrollment
            if ($this->enrollmentModel->drop($studentId, $courseId)) {
                Response::success(null, 'Enrollment dropped successfully');
            } else {
                Response::error('Failed to drop enrollment', 500);
            }
            
        } catch (Exception $e) {
            Response::error('Error dropping enrollment: ' . $e->getMessage(), 500);
        }
    }
}
