# API Quick Reference Card

## Base URL
```
http://localhost/ulo-backend/main.php
```

## Authentication Header
```
Authorization: Bearer {token}
```

---

## 🔐 AUTHENTICATION

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/auth/register` | ✗ | Register new user |
| POST | `/api/auth/login` | ✗ | Login & get JWT token |

**Register Request:**
```json
{
  "full_name": "John Doe",
  "email": "john@example.com",
  "password": "SecurePass@123",
  "confirm_password": "SecurePass@123",
  "phone": "+1234567890",
  "address": "123 Main St"
}
```

**Login Request:**
```json
{
  "email": "john@example.com",
  "password": "SecurePass@123"
}
```

---

## 👤 USER PROFILE

| Method | Endpoint | Auth | Role | Description |
|--------|----------|------|------|-------------|
| GET | `/api/users/profile` | ✓ | Any | Get own profile |
| PUT | `/api/users/profile` | ✓ | Any | Update own profile |

**Update Profile Request:**
```json
{
  "full_name": "Jane Doe",
  "phone": "+0987654321",
  "address": "456 Oak Ave",
  "current_password": "OldPass@123",
  "new_password": "NewPass@123"
}
```

---

## 📚 COURSES

| Method | Endpoint | Auth | Role | Description |
|--------|----------|------|------|-------------|
| POST | `/api/courses` | ✓ | Admin | Create course |
| GET | `/api/courses` | ✓ | Any | List courses |
| GET | `/api/courses/{id}` | ✓ | Any | Get course details |
| PUT | `/api/courses/{id}` | ✓ | Admin | Update course |

**Create Course Request:**
```json
{
  "course_code": "CS101",
  "title": "Introduction to CS",
  "description": "Learn the basics...",
  "credits": 3,
  "max_capacity": 50
}
```

**Query Parameters:**
- `limit` (default: 50)
- `offset` (default: 0)

---

## ✍️ ENROLLMENTS

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/enrollments` | ✓ | Enroll in course |
| GET | `/api/enrollments/student/{student_id}` | ✓ | Get student enrollments |
| DELETE | `/api/enrollments/{student_id}/{course_id}` | ✓ | Drop course |

**Enroll Request:**
```json
{
  "course_id": 1
}
```

---

## 🛡️ ADMIN

| Method | Endpoint | Auth | Role | Description |
|--------|----------|------|------|-------------|
| GET | `/api/admin/students` | ✓ | Admin | List all students |
| GET | `/api/admin/courses` | ✓ | Admin | List all courses |

**Query Parameters:**
- `limit` (default: 50)
- `offset` (default: 0)

---

## 📊 REPORTS

| Method | Endpoint | Auth | Role | Description |
|--------|----------|------|------|-------------|
| GET | `/api/reports/enrollments` | ✓ | Admin | Enrollment report |
| GET | `/api/reports/course-popularity` | ✓ | Admin | Course popularity |

**Query Parameters:**
- `limit` (default: 100)
- `offset` (default: 0)

---

## HTTP Status Codes

| Code | Meaning | Example |
|------|---------|---------|
| 200 | OK | Request successful |
| 201 | Created | Resource created |
| 400 | Bad Request | Invalid input |
| 401 | Unauthorized | Missing/invalid token |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable | Validation error |
| 500 | Server Error | Internal error |

---

## Response Format

**Success (200-201):**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    // response data
  }
}
```

**Error (4xx-5xx):**
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field": "Specific error message"
  }
}
```

---

## Password Requirements

- Minimum 8 characters
- At least 1 UPPERCASE letter
- At least 1 lowercase letter
- At least 1 digit (0-9)
- At least 1 special character (@$!%*?&)

✅ Example: `SecurePass@123`
❌ Example: `password123`

---

## cURL Examples

### Register
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

### Login
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "SecurePass@123"
  }'
```

### Get Profile
```bash
curl -X GET http://localhost/api/users/profile \
  -H "Authorization: Bearer eyJhbGc..."
```

### Get Courses
```bash
curl -X GET "http://localhost/api/courses?limit=10&offset=0" \
  -H "Authorization: Bearer eyJhbGc..."
```

### Enroll
```bash
curl -X POST http://localhost/api/enrollments \
  -H "Authorization: Bearer eyJhbGc..." \
  -H "Content-Type: application/json" \
  -d '{"course_id": 1}'
```

---

## Common Errors

| Error | Cause | Solution |
|-------|-------|----------|
| Authorization header missing | No token provided | Add Bearer token to header |
| Invalid token signature | Token tampered with | Generate new token by logging in |
| Token has expired | Token older than 1 hour | Login again to get new token |
| Admin access required | Non-admin user | Use admin token |
| Already enrolled in this course | Student enrolled | Check enrollments first |
| Course is at maximum capacity | No seats available | Check course details |
| Email already registered | Duplicate email | Use different email |
| Validation failed | Invalid input | Check field requirements |

---

## Encryption

### Encrypted Fields
- ✓ Email address
- ✓ Phone number
- ✓ Address

### NOT Encrypted
- ✗ Password (HASHED instead)
- ✗ User ID
- ✗ Course information
- ✗ Enrollment status

---

## Token Lifetime

- **Access Token:** 1 hour (3600 seconds)
- **Refresh Token:** 7 days (604800 seconds) - Reserved for future use

---

## Field Limits

| Field | Type | Max Length | Required |
|-------|------|-----------|----------|
| full_name | string | 255 | Yes |
| email | string | 255 | Yes |
| password | string | - | Yes* |
| phone | string | 20 | No |
| address | string | 500 | No |
| course_code | string | 20 | Yes |
| title | string | 255 | Yes |
| credits | integer | - | Yes |
| max_capacity | integer | - | No |

*Required for registration/login

---

## Database Encryption

All sensitive data is encrypted using **AES-256-GCM**:
- Each encryption uses a random 12-byte IV
- Authentication tag validates data integrity
- Stored as JSON with encrypted data, IV, and tag

---

## Security Headers

Automatically included:
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type, Authorization`
- `Content-Type: application/json`

---

## Pagination

Use `limit` and `offset` query parameters:

```
GET /api/courses?limit=20&offset=40
```

Response includes:
```json
{
  "courses": [...],
  "total": 150,
  "limit": 20,
  "offset": 40
}
```

---

## API Changelog

### Version 1.0.0 (2024-01-15)
- ✅ Initial release
- ✅ 17 endpoints
- ✅ JWT authentication
- ✅ AES-256-GCM encryption
- ✅ Full admin capabilities
- ✅ Reporting & analytics

---

## Support Resources

- 📖 [Full API Documentation](./API_DOCUMENTATION.md)
- 🧪 [Setup & Testing Guide](./SETUP_AND_TESTING.md)
- 📋 [Implementation Summary](./IMPLEMENTATION_SUMMARY.md)
- 📦 [Postman Collection](./Student_Enrollment_API.postman_collection.json)

---

**Last Updated:** January 2024
**Status:** Production Ready
