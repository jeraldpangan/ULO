# Student Course Enrollment System - Backend

A comprehensive, secure REST API for managing student course enrollments with advanced security features including JWT authentication and AES-256-GCM encryption.

## 🔐 Security Features

### AES-256-GCM Encryption
All sensitive user data is encrypted using AES-256-GCM:
- **Email addresses** - encrypted before storage
- **Phone numbers** - encrypted before storage  
- **Addresses** - encrypted before storage

Each encryption includes:
- Randomly generated 12-byte IV
- Authentication tag for data integrity
- Secure storage format with encryption metadata

### Password Security
- Passwords are **hashed** using PHP's `password_hash()` with BCRYPT algorithm
- **NOT encrypted** - hashing is one-way and cannot be reversed
- Password verification uses timing-safe comparison

### JWT Authentication
- Custom HS256 JWT implementation
- 1-hour token expiry (configurable)
- Bearer token in Authorization header
- Timing-safe signature verification

## 📋 API Overview

### 17 Total Endpoints

**Authentication (2)**
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user

**Profile Management (2)**
- `GET /api/users/profile` - Get user profile
- `PUT /api/users/profile` - Update user profile

**Courses (4)**
- `POST /api/courses` - Create course (admin)
- `GET /api/courses` - List courses
- `GET /api/courses/{id}` - Get course details
- `PUT /api/courses/{id}` - Update course (admin)

**Enrollments (3)**
- `POST /api/enrollments` - Enroll in course
- `GET /api/enrollments/student/{id}` - Get student enrollments
- `DELETE /api/enrollments/{student_id}/{course_id}` - Drop course

**Admin (2)**
- `GET /api/admin/students` - List all students
- `GET /api/admin/courses` - List all courses

**Reports (2)**
- `GET /api/reports/enrollments` - Enrollment statistics
- `GET /api/reports/course-popularity` - Course popularity analysis

## 🚀 Quick Start

### Prerequisites
- PHP 7.4+
- MySQL/MariaDB
- OpenSSL extension
- PDO MySQL extension

### Installation

1. **Clone the repository**
   ```bash
   cd ULO/ulo-backend
   ```

2. **Set up the database**
   ```bash
   mysql -u jeraldpangan -p balmondiyotmiya < database_schema.sql
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your configuration
   ```

4. **Test the API**
   ```bash
   # Register a user
   curl -X POST http://localhost/api/auth/register \
     -H "Content-Type: application/json" \
     -d '{
       "full_name": "John Doe",
       "email": "john@example.com",
       "password": "SecurePass@123",
       "confirm_password": "SecurePass@123"
     }'
   ```

### Using Postman

1. Import `Student_Enrollment_API.postman_collection.json` into Postman
2. Set `base_url` variable to `http://localhost/ulo-backend/main.php`
3. Use the collection to test all endpoints

## 📁 Project Structure

```
ulo-backend/
├── config/                          # Configuration files
│   ├── Connection.php              # Database connection
│   ├── Encryption.php              # AES-256-GCM config
│   └── JWT.php                     # JWT settings
│
├── controllers/                     # Route handlers
│   ├── AuthController.php          # Authentication
│   ├── UserController.php          # User profile
│   ├── CourseController.php        # Course management
│   ├── EnrollmentController.php    # Enrollments
│   ├── AdminController.php         # Admin functions
│   └── ReportController.php        # Reports
│
├── models/                          # Data access layer
│   ├── User.php                    # User operations
│   ├── Course.php                  # Course operations
│   └── Enrollment.php              # Enrollment operations
│
├── helpers/                         # Utility classes
│   ├── Encryptor.php              # AES-256-GCM encryption
│   ├── JWT.php                    # JWT token handling
│   ├── Response.php               # JSON responses
│   └── Validator.php              # Input validation
│
├── middleware/                      # Middleware
│   └── AuthMiddleware.php          # JWT verification
│
├── main.php                         # API router
├── database_schema.sql              # Database setup
├── .env.example                     # Environment template
├── API_DOCUMENTATION.md             # API reference
├── SETUP_AND_TESTING.md            # Testing guide
├── IMPLEMENTATION_SUMMARY.md        # Implementation details
└── Student_Enrollment_API.postman_collection.json
```

## 🔒 Security Checklist

- ✅ AES-256-GCM encryption for sensitive data
- ✅ Password hashing with BCRYPT
- ✅ JWT authentication
- ✅ Input validation and sanitization
- ✅ Prepared statements (SQL injection prevention)
- ✅ CORS headers
- ✅ Proper HTTP status codes
- ✅ Error handling without sensitive information
- ✅ Role-based access control
- ✅ Login audit trail

## 📖 Documentation

- **[API_DOCUMENTATION.md](./API_DOCUMENTATION.md)** - Complete API reference with examples
- **[SETUP_AND_TESTING.md](./SETUP_AND_TESTING.md)** - Setup and testing guide with scenarios
- **[IMPLEMENTATION_SUMMARY.md](./IMPLEMENTATION_SUMMARY.md)** - Implementation details and file structure

## 🧪 Testing

### Manual Testing with cURL

**Register:**
```bash
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "Jane Doe",
    "email": "jane@example.com",
    "password": "TestPass@123",
    "confirm_password": "TestPass@123"
  }'
```

**Login:**
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "jane@example.com", "password": "TestPass@123"}'
```

**Authenticated Request:**
```bash
curl -X GET http://localhost/api/users/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Automated Testing

Run the Postman collection:
```bash
newman run Student_Enrollment_API.postman_collection.json \
  --environment postman_environment.json
```

## 🗄️ Database Schema

### Users Table
- Stores user information with encrypted sensitive data
- Tracks login history
- Supports role-based access (student/admin)

### Courses Table
- Course information and metadata
- Enrollment capacity tracking
- Status management

### Enrollments Table
- Student-course relationships
- Enrollment status tracking
- Grade recording

### Supporting Tables
- `login_logs` - Login audit trail
- `refresh_tokens` - Token management

## 🔑 Configuration

### Encryption Key

Generate a new encryption key:
```bash
php -r "echo bin2hex(openssl_random_pseudo_bytes(32));"
```

Update `config/Encryption.php`:
```php
define('ENCRYPTION_KEY', hex2bin('your-generated-key'));
```

### JWT Secret

Update `config/JWT.php`:
```php
define('JWT_SECRET', 'your-strong-secret-key-32-chars-minimum');
```

## 🚨 Error Handling

All errors return standardized JSON responses:

```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field": "Specific error"
  }
}
```

HTTP Status Codes:
- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## 🔄 Request/Response Flow

```
Client Request
    ↓
Route Parser (main.php)
    ↓
Controller Handler
    ↓
Input Validation
    ↓
Business Logic (Models)
    ↓
Database Operations (encrypted when needed)
    ↓
Response Handler (Response class)
    ↓
JSON Response to Client
```

## 🛠️ Development

### Adding a New Endpoint

1. Create method in appropriate controller
2. Add route handler in `main.php`
3. Update documentation
4. Test with Postman or cURL

Example:
```php
// In controller
public function newAction() {
    try {
        // Logic here
        Response::success($data, 'Success message', 200);
    } catch (Exception $e) {
        Response::error('Error: ' . $e->getMessage(), 500);
    }
}

// In main.php
elseif ($route_parts[0] === 'resource' && $route_parts[1] === 'action') {
    $controller->newAction();
}
```

## 📊 Database Optimization

- Indexes on frequently queried columns
- Foreign key constraints for data integrity
- Proper data types and field sizes
- Connection pooling via PDO persistent connections

## 🚀 Deployment

### Production Checklist

- [ ] Update database credentials
- [ ] Change JWT secret key
- [ ] Change encryption key
- [ ] Enable HTTPS
- [ ] Configure CORS for frontend URL
- [ ] Set `APP_ENV=production`
- [ ] Disable debug mode
- [ ] Set up database backups
- [ ] Configure monitoring
- [ ] Enable error logging
- [ ] Test all endpoints
- [ ] Set up rate limiting
- [ ] Configure CDN if needed

### Environment Variables

Create `.env` file in production:
```env
DB_SERVER=prod-server
DB_USER=prod_user
DB_PASSWORD=secure_password
JWT_SECRET=production_secret_key
ENCRYPTION_KEY=production_encryption_key
APP_ENV=production
APP_DEBUG=false
```

## 🤝 Contributing

1. Follow the existing code structure
2. Use prepared statements for database queries
3. Validate and sanitize all inputs
4. Include error handling
5. Update documentation
6. Test thoroughly

## 📝 License

This project is part of the ULO (University Learning Operations) system.

## 👥 Support

For issues or questions:
1. Check documentation in API_DOCUMENTATION.md
2. Review SETUP_AND_TESTING.md for testing scenarios
3. Check database schema for data structure
4. Review error messages and HTTP status codes

## 🎯 Future Enhancements

- [ ] Refresh token implementation
- [ ] Email verification
- [ ] Two-factor authentication
- [ ] Course prerequisites
- [ ] Grade calculation & GPA
- [ ] Student transcripts
- [ ] Course scheduling
- [ ] Notification system
- [ ] File uploads
- [ ] Bulk operations

---

**Built with:** PHP 7.4+ | MySQL/MariaDB | OpenSSL | JWT | AES-256-GCM

**Last Updated:** January 2024

**Version:** 1.0.0
