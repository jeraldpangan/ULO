<?php
/**
 * Report Controller
 * Handles report generation
 */

require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class ReportController {
    
    private $enrollmentModel;
    
    public function __construct() {
        $this->enrollmentModel = new Enrollment();
    }
    
    /**
     * Get enrollments report
     * GET /api/reports/enrollments
     */
    public function getEnrollmentsReport() {
        try {
            // Verify authentication and admin role
            $authUser = AuthMiddleware::verify();
            AuthMiddleware::isAdmin($authUser);
            
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            
            $enrollments = $this->enrollmentModel->getAllEnrollments($limit, $offset);
            
            // Calculate statistics
            $stats = [
                'total_enrollments' => count($enrollments),
                'by_status' => [
                    'enrolled' => 0,
                    'completed' => 0,
                    'dropped' => 0
                ]
            ];
            
            foreach ($enrollments as $enrollment) {
                if (isset($stats['by_status'][$enrollment['status']])) {
                    $stats['by_status'][$enrollment['status']]++;
                }
            }
            
            Response::success([
                'enrollments' => $enrollments,
                'statistics' => $stats,
                'limit' => $limit,
                'offset' => $offset
            ], 'Enrollments report retrieved successfully');
            
        } catch (Exception $e) {
            Response::error('Error retrieving report: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Get course popularity report
     * GET /api/reports/course-popularity
     */
    public function getCoursePopularityReport() {
        try {
            // Verify authentication and admin role
            $authUser = AuthMiddleware::verify();
            AuthMiddleware::isAdmin($authUser);
            
            $courses = $this->enrollmentModel->getCoursePopularity();
            
            // Calculate statistics
            $stats = [
                'total_courses' => count($courses),
                'total_enrollments' => 0,
                'average_enrollments' => 0,
                'most_popular' => null,
                'least_popular' => null
            ];
            
            foreach ($courses as $course) {
                $stats['total_enrollments'] += $course['total_enrollments'];
                
                if ($stats['most_popular'] === null || $course['total_enrollments'] > $stats['most_popular']['total_enrollments']) {
                    $stats['most_popular'] = $course;
                }
                
                if ($stats['least_popular'] === null || $course['total_enrollments'] < $stats['least_popular']['total_enrollments']) {
                    $stats['least_popular'] = $course;
                }
            }
            
            if ($stats['total_courses'] > 0) {
                $stats['average_enrollments'] = round($stats['total_enrollments'] / $stats['total_courses'], 2);
            }
            
            Response::success([
                'courses' => $courses,
                'statistics' => $stats
            ], 'Course popularity report retrieved successfully');
            
        } catch (Exception $e) {
            Response::error('Error retrieving report: ' . $e->getMessage(), 500);
        }
    }
}
