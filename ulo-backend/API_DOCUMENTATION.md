# Student Course Enrollment System - Backend API

## Overview
A comprehensive REST API for managing student course enrollments with JWT authentication and AES-256-GCM encryption for sensitive data.

## Features
- **JWT Authentication**: Custom JWT implementation for secure API access
- **AES-256-GCM Encryption**: End-to-end encryption for sensitive user data (email, phone, address)
- **Role-Based Access Control**: Admin and student roles with different permissions
- **Input Validation**: Comprehensive validation for all API endpoints
- **Error Handling**: Standardized error responses with proper HTTP status codes
- **Course Management**: Create, read, and update courses
- **Enrollment System**: Students can enroll in courses with capacity checks
- **Admin Reporting**: Comprehensive enrollment and course popularity reports
- **Login Audit Trail**: Tracks all login attempts

## Database Setup

### Prerequisites
- MySQL/MariaDB server running
- PHP 7.4+ with OpenSSL extension
- PDO MySQL extension

### Initialize Database

1. Log in to MySQL:
```bash
mysql -u root -p
```

2. Run the schema file:
```bash
source database_schema.sql
```

3. Or manually execute the SQL in `database_schema.sql`

### Create Admin User (Optional)
```sql
INSERT INTO users (full_name, email, email_encrypted, password, role, status)
VALUES (
    'Admin User',
    'admin@university.edu',
    NULL,
    '$2y$10$YourHashedPasswordHere',
    'admin',
    'active'
);
```

## Configuration

### Database Connection
Edit `config/Connection.php` to match your database credentials:
```php
define("SERVER", "localhost");
define("USER", "jeraldpangan");
define("PASSWORD", "balmondiyotmiya");
define("DB", "db_student_service");
```

### Encryption Keys
The encryption keys are defined in:
- **Encryption Key**: `config/Encryption.php`
- **JWT Secret**: `config/JWT.php`

**Important**: Change these keys in production!

## API Endpoints

### Authentication

#### Register User
```
POST /api/auth/register
Content-Type: application/json

{
  "full_name": "John Doe",
  "email": "john@example.com",
  "password": "SecurePass@123",
  "confirm_password": "SecurePass@123",
  "phone": "+1234567890",
  "address": "123 Main St"
}

Response (201):
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user_id": 1,
    "email": "john@example.com",
    "token": "eyJhbGc..."
  }
}
```

#### Login
```
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "SecurePass@123"
}

Response (200):
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user_id": 1,
    "email": "john@example.com",
    "role": "student",
    "token": "eyJhbGc..."
  }
}
```

### User Profile

#### Get Profile
```
GET /api/users/profile
Authorization: Bearer <token>

Response (200):
{
  "success": true,
  "message": "Profile retrieved successfully",
  "data": {
    "id": 1,
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "address": "123 Main St",
    "role": "student",
    "status": "active",
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z"
  }
}
```

#### Update Profile
```
PUT /api/users/profile
Authorization: Bearer <token>
Content-Type: application/json

{
  "full_name": "Jane Doe",
  "phone": "+0987654321",
  "address": "456 Oak Ave"
}

Response (200):
{
  "success": true,
  "message": "Profile updated successfully",
  "data": { ... }
}
```

### Courses

#### Create Course (Admin Only)
```
POST /api/courses
Authorization: Bearer <admin_token>
Content-Type: application/json

{
  "course_code": "CS101",
  "title": "Introduction to Computer Science",
  "description": "Learn the basics...",
  "credits": 3,
  "max_capacity": 50
}

Response (201):
{
  "success": true,
  "message": "Course created successfully",
  "data": {
    "id": 1,
    "course_code": "CS101",
    "title": "Introduction to Computer Science",
    ...
  }
}
```

#### Get All Courses
```
GET /api/courses?limit=50&offset=0
Authorization: Bearer <token>

Response (200):
{
  "success": true,
  "message": "Courses retrieved successfully",
  "data": {
    "courses": [ ... ],
    "total": 25,
    "limit": 50,
    "offset": 0
  }
}
```

#### Get Course by ID
```
GET /api/courses/{course_id}
Authorization: Bearer <token>

Response (200):
{
  "success": true,
  "message": "Course retrieved successfully",
  "data": { ... }
}
```

#### Update Course (Admin Only)
```
PUT /api/courses/{course_id}
Authorization: Bearer <admin_token>
Content-Type: application/json

{
  "title": "Advanced CS",
  "credits": 4,
  "max_capacity": 40
}

Response (200):
{
  "success": true,
  "message": "Course updated successfully",
  "data": { ... }
}
```

### Enrollments

#### Enroll in Course
```
POST /api/enrollments
Authorization: Bearer <student_token>
Content-Type: application/json

{
  "course_id": 1
}

Response (201):
{
  "success": true,
  "message": "Enrolled successfully",
  "data": {
    "id": 1,
    "student_id": 1,
    "course_id": 1,
    "status": "enrolled",
    "enrolled_at": "2024-01-15T10:30:00Z"
  }
}
```

#### Get Student Enrollments
```
GET /api/enrollments/student/{student_id}
Authorization: Bearer <token>

Response (200):
{
  "success": true,
  "message": "Enrollments retrieved successfully",
  "data": {
    "student_id": 1,
    "enrollments": [ ... ],
    "total": 5
  }
}
```

#### Drop Course
```
DELETE /api/enrollments/{student_id}/{course_id}
Authorization: Bearer <token>

Response (200):
{
  "success": true,
  "message": "Enrollment dropped successfully"
}
```

### Admin Routes

#### Get All Students
```
GET /api/admin/students?limit=50&offset=0
Authorization: Bearer <admin_token>

Response (200):
{
  "success": true,
  "message": "Students retrieved successfully",
  "data": {
    "students": [ ... ],
    "total": 150,
    "limit": 50,
    "offset": 0
  }
}
```

#### Get All Courses
```
GET /api/admin/courses?limit=50&offset=0
Authorization: Bearer <admin_token>

Response (200):
{
  "success": true,
  "message": "Courses retrieved successfully",
  "data": {
    "courses": [ ... ],
    "total": 25,
    "limit": 50,
    "offset": 0
  }
}
```

### Reports

#### Enrollments Report
```
GET /api/reports/enrollments?limit=100&offset=0
Authorization: Bearer <admin_token>

Response (200):
{
  "success": true,
  "message": "Enrollments report retrieved successfully",
  "data": {
    "enrollments": [ ... ],
    "statistics": {
      "total_enrollments": 250,
      "by_status": {
        "enrolled": 180,
        "completed": 60,
        "dropped": 10
      }
    }
  }
}
```

#### Course Popularity Report
```
GET /api/reports/course-popularity
Authorization: Bearer <admin_token>

Response (200):
{
  "success": true,
  "message": "Course popularity report retrieved successfully",
  "data": {
    "courses": [ ... ],
    "statistics": {
      "total_courses": 25,
      "total_enrollments": 250,
      "average_enrollments": 10.0,
      "most_popular": { ... },
      "least_popular": { ... }
    }
  }
}
```

## Security Features

### AES-256-GCM Encryption
The system encrypts the following sensitive data:
- **Email address**: Encrypted and stored in `email_encrypted`
- **Phone number**: Encrypted and stored in `phone_encrypted`
- **Address**: Encrypted and stored in `address_encrypted`

**Note**: Passwords are hashed using `password_hash()`, not encrypted.

### Encryption Process
1. Generate random 12-byte IV for each encryption
2. Encrypt data using AES-256-GCM
3. Store encrypted data, IV, and authentication tag in database

### Decryption Process
1. Retrieve encrypted data, IV, and authentication tag
2. Decrypt using AES-256-GCM
3. Validate authentication tag

## HTTP Status Codes

- **200**: OK - Request successful
- **201**: Created - Resource successfully created
- **400**: Bad Request - Invalid input or validation error
- **401**: Unauthorized - Missing or invalid authentication token
- **403**: Forbidden - Insufficient permissions
- **404**: Not Found - Resource not found
- **422**: Unprocessable Entity - Validation failed
- **500**: Internal Server Error - Server-side error

## Password Requirements

Passwords must contain:
- At least 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- At least one special character (@$!%*?&)

## Error Response Format

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": "Specific error for field"
  }
}
```

## Testing with cURL

### Register a user:
```bash
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "John Doe",
    "email": "john@example.com",
    "password": "SecurePass@123",
    "confirm_password": "SecurePass@123"
  }'
```

### Login:
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "SecurePass@123"
  }'
```

### Get profile with token:
```bash
curl -X GET http://localhost/api/users/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Development Notes

### Adding New Endpoints
1. Create or update a controller
2. Add the route logic in `main.php`
3. Return responses using the `Response` class
4. Update this documentation

### File Structure
```
ulo-backend/
├── config/
│   ├── Connection.php
│   ├── Encryption.php
│   └── JWT.php
├── controllers/
│   ├── AuthController.php
│   ├── UserController.php
│   ├── CourseController.php
│   ├── EnrollmentController.php
│   ├── AdminController.php
│   └── ReportController.php
├── models/
│   ├── User.php
│   ├── Course.php
│   └── Enrollment.php
├── helpers/
│   ├── Encryptor.php
│   ├── JWT.php
│   ├── Response.php
│   └── Validator.php
├── middleware/
│   └── AuthMiddleware.php
├── main.php
└── database_schema.sql
```

## Future Enhancements
- Refresh token implementation
- Email verification for registration
- Two-factor authentication
- Course prerequisites
- Grade calculation and GPA tracking
- Student transcript generation
- Course scheduling and timetable
- Notification system

## Support
For issues or questions, please refer to the code comments or contact the development team.
