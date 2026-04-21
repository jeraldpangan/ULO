<?php
/**
 * API Entry Point & Router
 * Student Course Enrollment System
 */

// Enable error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Set headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load dependencies
require_once __DIR__ . '/config/Connection.php';
require_once __DIR__ . '/helpers/Response.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/CourseController.php';
require_once __DIR__ . '/controllers/EnrollmentController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/ReportController.php';

// Get request path
$request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove base path and get API route
$base_path = '/api';
if (strpos($request_path, $base_path) === 0) {
    $route = substr($request_path, strlen($base_path));
} else {
    Response::error('Invalid API endpoint', 400);
}

// Parse route
$route_parts = explode('/', trim($route, '/'));

// Route handling
try {
    // Auth routes
    if ($route_parts[0] === 'auth') {
        $authController = new AuthController();
        
        if ($route_parts[1] === 'register' && $request_method === 'POST') {
            $authController->register();
        } elseif ($route_parts[1] === 'login' && $request_method === 'POST') {
            $authController->login();
        }
    }
    
    // User routes
    elseif ($route_parts[0] === 'users') {
        $userController = new UserController();
        
        if ($route_parts[1] === 'profile' && $request_method === 'GET') {
            $userController->getProfile();
        } elseif ($route_parts[1] === 'profile' && $request_method === 'PUT') {
            $userController->updateProfile();
        }
    }
    
    // Course routes
    elseif ($route_parts[0] === 'courses') {
        $courseController = new CourseController();
        
        if (empty($route_parts[1]) && $request_method === 'POST') {
            $courseController->create();
        } elseif (empty($route_parts[1]) && $request_method === 'GET') {
            $courseController->getAll();
        } elseif (!empty($route_parts[1]) && $request_method === 'GET') {
            $courseId = (int)$route_parts[1];
            $courseController->getById($courseId);
        } elseif (!empty($route_parts[1]) && $request_method === 'PUT') {
            $courseId = (int)$route_parts[1];
            $courseController->update($courseId);
        }
    }
    
    // Enrollment routes
    elseif ($route_parts[0] === 'enrollments') {
        $enrollmentController = new EnrollmentController();
        
        if (empty($route_parts[1]) && $request_method === 'POST') {
            $enrollmentController->create();
        } elseif ($route_parts[1] === 'student' && !empty($route_parts[2]) && $request_method === 'GET') {
            $studentId = (int)$route_parts[2];
            $enrollmentController->getStudentEnrollments($studentId);
        } elseif (!empty($route_parts[1]) && !empty($route_parts[2]) && $request_method === 'DELETE') {
            $studentId = (int)$route_parts[1];
            $courseId = (int)$route_parts[2];
            $enrollmentController->drop($studentId, $courseId);
        }
    }
    
    // Admin routes
    elseif ($route_parts[0] === 'admin') {
        $adminController = new AdminController();
        
        if ($route_parts[1] === 'students' && $request_method === 'GET') {
            $adminController->getAllStudents();
        } elseif ($route_parts[1] === 'courses' && $request_method === 'GET') {
            $adminController->getAllCourses();
        }
    }
    
    // Report routes
    elseif ($route_parts[0] === 'reports') {
        $reportController = new ReportController();
        
        if ($route_parts[1] === 'enrollments' && $request_method === 'GET') {
            $reportController->getEnrollmentsReport();
        } elseif ($route_parts[1] === 'course-popularity' && $request_method === 'GET') {
            $reportController->getCoursePopularityReport();
        }
    }
    
    // Route not found
    else {
        Response::notFound('Endpoint not found');
    }
    
} catch (Exception $e) {
    Response::error('Server error: ' . $e->getMessage(), 500);
}
