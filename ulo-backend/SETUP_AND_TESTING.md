# Setup & Testing Guide

## Quick Start

### 1. Database Setup

Execute the following command to create the database and tables:

```bash
mysql -u jeraldpangan -p balmondiyotmiya < database_schema.sql
```

Or use MySQL Workbench:
1. Open `database_schema.sql`
2. Execute the script
3. Verify tables are created

### 2. Update Configuration (if needed)

Edit `config/Connection.php` if using different database credentials:
```php
define("SERVER", "localhost");
define("USER", "your_user");
define("PASSWORD", "your_password");
define("DB", "db_student_service");
```

### 3. Test the API

#### Using cURL:

**Register a new student:**
```bash
curl -X POST http://localhost/ulo-backend/main.php/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "John Doe",
    "email": "john@example.com",
    "password": "SecurePass@123",
    "confirm_password": "SecurePass@123",
    "phone": "+1234567890",
    "address": "123 Main Street"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user_id": 1,
    "email": "john@example.com",
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
  }
}
```

**Login:**
```bash
curl -X POST http://localhost/ulo-backend/main.php/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "SecurePass@123"
  }'
```

**Get Profile (with JWT token):**
```bash
curl -X GET http://localhost/ulo-backend/main.php/api/users/profile \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

### Using Postman

1. Import the provided Postman collection (if available)
2. Set the `base_url` variable to `http://localhost/ulo-backend/main.php`
3. Run requests from the collection

## Testing Scenarios

### Scenario 1: Student Registration and Profile

1. **Register a student**
   - POST /api/auth/register
   - Fill in all required fields
   - Verify email is encrypted in database

2. **Login as student**
   - POST /api/auth/login
   - Save the JWT token
   - Use token in subsequent requests

3. **View profile**
   - GET /api/users/profile
   - Verify email, phone, and address are decrypted
   - Confirm sensitive data is properly displayed

4. **Update profile**
   - PUT /api/users/profile
   - Update phone or address
   - Verify encryption of updated data

### Scenario 2: Course Management (Admin Only)

1. **Create admin account** (manual SQL insertion)
   ```sql
   INSERT INTO users (full_name, email, email_encrypted, password, role, status)
   VALUES (
     'Admin User',
     'admin@university.edu',
     NULL,
     '$2y$10$...',  -- hashed password
     'admin',
     'active'
   );
   ```

2. **Login as admin**
   - POST /api/auth/login
   - Use admin credentials
   - Save admin JWT token

3. **Create courses**
   - POST /api/courses
   - Use admin token
   - Create multiple courses with different capacities

4. **View all courses**
   - GET /api/admin/courses
   - Verify pagination works
   - Check enrollment counts

### Scenario 3: Student Enrollment

1. **Login as student**
   - Save JWT token

2. **View available courses**
   - GET /api/courses
   - List all active courses

3. **Enroll in course**
   - POST /api/enrollments
   - Supply valid course_id
   - Verify enrollment created with "enrolled" status

4. **View enrollments**
   - GET /api/enrollments/student/{student_id}
   - Verify course details included
   - Check enrollment status

5. **Drop course**
   - DELETE /api/enrollments/{student_id}/{course_id}
   - Verify status changes to "dropped"

### Scenario 4: Reports and Analytics

1. **Login as admin**
   - Use admin token

2. **View enrollment report**
   - GET /api/reports/enrollments
   - Verify statistics by status
   - Check pagination

3. **View course popularity**
   - GET /api/reports/course-popularity
   - Verify enrollment percentages
   - Identify most/least popular courses

## Encryption Verification

### Check Encrypted Data in Database

```sql
-- View encrypted email
SELECT id, email, email_encrypted FROM users LIMIT 1;

-- Should show:
-- id: 1
-- email: john@example.com
-- email_encrypted: {"encrypted":"base64_string","iv":"base64_string","tag":"base64_string"}
```

### Manual Decryption Test (if needed)

Use the Encryptor helper class:
```php
require_once 'helpers/Encryptor.php';

$encrypted_json = '{"encrypted":"...","iv":"...","tag":"..."}';
$decrypted = Encryptor::decryptFromStorage($encrypted_json);
echo $decrypted; // Should output: john@example.com
```

## Error Handling Tests

### Test Validation Errors

**Register with weak password:**
```bash
curl -X POST http://localhost/ulo-backend/main.php/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "John Doe",
    "email": "john@example.com",
    "password": "weak",
    "confirm_password": "weak"
  }'
```

**Response (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "Password": "Password must be at least 8 characters with uppercase, lowercase, number, and special character"
  }
}
```

### Test Authentication Errors

**Request without token:**
```bash
curl -X GET http://localhost/ulo-backend/main.php/api/users/profile
```

**Response (401):**
```json
{
  "success": false,
  "message": "Authorization header missing"
}
```

### Test Authorization Errors

**Non-admin attempting to create course:**
```bash
curl -X POST http://localhost/ulo-backend/main.php/api/courses \
  -H "Authorization: Bearer {student_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "course_code": "CS101",
    "title": "Introduction to CS",
    "credits": 3
  }'
```

**Response (403):**
```json
{
  "success": false,
  "message": "Admin access required"
}
```

## Performance Testing

### Bulk User Creation
```bash
for i in {1..100}; do
  curl -X POST http://localhost/ulo-backend/main.php/api/auth/register \
    -H "Content-Type: application/json" \
    -d "{
      \"full_name\": \"Student $i\",
      \"email\": \"student$i@example.com\",
      \"password\": \"SecurePass@123\",
      \"confirm_password\": \"SecurePass@123\"
    }"
done
```

### Load Test
Use Apache Bench:
```bash
ab -n 1000 -c 10 http://localhost/ulo-backend/main.php/api/courses
```

## Troubleshooting

### Issue: "Connection failed"
- Check MySQL is running
- Verify database credentials in `config/Connection.php`
- Ensure database and tables exist

### Issue: "Authorization header missing"
- Verify token is passed in Authorization header
- Use format: `Authorization: Bearer <token>`
- Check token hasn't expired (1 hour default)

### Issue: "Decryption failed"
- Verify encryption key in `config/Encryption.php` matches
- Check encrypted data format in database
- Ensure OpenSSL is enabled

### Issue: "Admin access required"
- Verify user has admin role in database
- Check user role is correctly set to 'admin'
- Confirm admin token is being used

## Database Inspection

### View all users
```sql
SELECT id, full_name, email, role, status, created_at FROM users;
```

### View all courses
```sql
SELECT id, course_code, title, credits, max_capacity FROM courses;
```

### View enrollments
```sql
SELECT e.id, u.full_name as student, c.title as course, e.status, e.enrolled_at 
FROM enrollments e
JOIN users u ON e.student_id = u.id
JOIN courses c ON e.course_id = c.id;
```

### View login logs
```sql
SELECT u.full_name, l.login_type, l.ip_address, l.created_at 
FROM login_logs l
JOIN users u ON l.user_id = u.id
ORDER BY l.created_at DESC
LIMIT 20;
```

## Production Checklist

- [ ] Change JWT_SECRET in `config/JWT.php`
- [ ] Change ENCRYPTION_KEY in `config/Encryption.php`
- [ ] Use environment variables for sensitive credentials
- [ ] Enable HTTPS
- [ ] Set up database backups
- [ ] Configure CORS properly
- [ ] Disable error display (set `display_errors` to 0)
- [ ] Set up rate limiting
- [ ] Enable CSRF protection if needed
- [ ] Set up monitoring and logging
- [ ] Test all endpoints thoroughly
