# ENROLLMENT SYSTEM - TESTING GUIDE

## System Overview

This enrollment system allows students to enroll in courses via AJAX without page reload. The system includes:

1. **Courses Table**: Stores course information (code, name, description, teacher, credits)
2. **Enrollments Table**: Tracks student enrollments with unique constraints
3. **AJAX Enrollment**: Real-time enrollment without page reload
4. **Security Features**: CSRF protection, authentication checks, input validation

## Login Credentials

- **Admin**: jesse@gmail.com / admin123
- **Teacher**: ogillee@gmail.com / teacher123
- **Student**: tally@gmail.com / student123

## Testing Steps

### Step 1: Login as Student
1. Navigate to http://localhost/ITE311-ZURITA/login
2. Login with student credentials
3. You'll be redirected to the student dashboard

### Step 2: View Available Courses
- The dashboard shows two sections:
  - **My Enrolled Courses** (left): Currently enrolled courses
  - **Available Courses** (right): Courses you can enroll in

### Step 3: Test AJAX Enrollment
1. Click the "Enroll" button on any available course
2. Observe the following (NO PAGE RELOAD):
   - Button shows "Enrolling..." with spinner
   - Success alert appears at the top
   - Course moves from Available to Enrolled section
   - Enrollment count in stats updates
   - Button disappears from available courses

### Step 4: Open Developer Tools (F12)
1. Go to Network tab
2. Click another "Enroll" button
3. Observe the AJAX POST request to `/course/enroll`
4. View the JSON response showing success status

## Security Testing (Vulnerability Checking)

### Test 1: Authorization Bypass
**Objective**: Verify unauthorized users cannot enroll

1. **Logout** from the application
2. Open browser console (F12)
3. Try to send enrollment request:
```javascript
fetch('http://localhost/ITE311-ZURITA/course/enroll', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'course_id=1'
})
.then(response => response.json())
.then(data => console.log(data));
```
4. **Expected Result**: 401 Unauthorized error with message "Unauthorized. Please login first."

### Test 2: SQL Injection
**Objective**: Test if SQL injection is prevented

1. Login as student
2. Open browser console
3. Modify the AJAX request with malicious SQL:
```javascript
// Get CSRF token
const csrfToken = document.querySelector('[name="csrf_test_name"]').value;

// Try SQL injection
fetch('http://localhost/ITE311-ZURITA/course/enroll', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `course_id=1 OR 1=1&csrf_test_name=${csrfToken}`
})
.then(response => response.json())
.then(data => console.log(data));
```
4. **Expected Result**: "Invalid course ID" error - SQL injection prevented by input validation

### Test 3: CSRF Protection
**Objective**: Verify CSRF tokens are required

1. Login as student
2. Try enrollment WITHOUT CSRF token:
```javascript
fetch('http://localhost/ITE311-ZURITA/course/enroll', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'course_id=1'
})
.then(response => console.log(response.status));
```
3. **Expected Result**: 403 Forbidden - CSRF validation failed

### Test 4: Data Tampering
**Objective**: Verify user_id comes from session, not client

1. Login as student
2. Try to enroll another user by modifying request:
```javascript
const csrfToken = document.querySelector('[name="csrf_test_name"]').value;

fetch('http://localhost/ITE311-ZURITA/course/enroll', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `course_id=1&user_id=999&csrf_test_name=${csrfToken}`
})
.then(response => response.json())
.then(data => console.log(data));
```
4. **Expected Result**: Enrollment uses session user_id, not the tampered value

### Test 5: Input Validation
**Objective**: Test validation of course existence

1. Login as student
2. Try to enroll in non-existent course:
```javascript
const csrfToken = document.querySelector('[name="csrf_test_name"]').value;

fetch('http://localhost/ITE311-ZURITA/course/enroll', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `course_id=99999&csrf_test_name=${csrfToken}`
})
.then(response => response.json())
.then(data => console.log(data));
```
3. **Expected Result**: "Course not found" error with 404 status

### Test 6: Duplicate Enrollment Prevention
**Objective**: Verify students can't enroll twice

1. Login as student
2. Enroll in a course successfully
3. Try to enroll in the same course again
4. **Expected Result**: "You are already enrolled in this course" error

### Test 7: Role-Based Access
**Objective**: Only students can enroll

1. Login as **Teacher** or **Admin**
2. Try to access enrollment endpoint:
```javascript
const csrfToken = document.querySelector('[name="csrf_test_name"]').value;

fetch('http://localhost/ITE311-ZURITA/course/enroll', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `course_id=1&csrf_test_name=${csrfToken}`
})
.then(response => response.json())
.then(data => console.log(data));
```
3. **Expected Result**: 403 Forbidden - "Only students can enroll in courses"

## Screenshots Required

1. **Database Structure**
   - phpMyAdmin showing `enrollments` table structure
   - phpMyAdmin showing `courses` table structure

2. **Student Dashboard**
   - Full dashboard showing both "My Enrolled Courses" and "Available Courses"
   - Stats showing enrollment count

3. **Developer Tools - Network Tab**
   - AJAX POST request to `/course/enroll`
   - Request payload showing `course_id` and `csrf_test_name`
   - Response showing `{"success":true,"message":"Successfully enrolled..."}`

4. **GitHub Repository**
   - Latest commit showing enrollment system implementation
   - Repository files including migrations, models, controllers, views

## Expected Behaviors

✅ **Success Cases**:
- Student can enroll in available courses
- No page reload during enrollment
- Real-time UI updates
- Success alerts displayed
- Enrollment count updates automatically

❌ **Prevented Cases**:
- Unauthorized access (not logged in)
- Non-student users enrolling
- SQL injection attempts
- Missing CSRF tokens
- Duplicate enrollments
- Invalid course IDs
- Data tampering (user_id modification)

## Database Verification

After testing, check the database:
```sql
-- View all enrollments
SELECT e.*, u.name as student_name, c.course_name 
FROM enrollments e
JOIN users u ON e.user_id = u.id
JOIN courses c ON e.course_id = c.id;

-- Verify unique constraint
SELECT user_id, course_id, COUNT(*) as count
FROM enrollments
GROUP BY user_id, course_id
HAVING COUNT(*) > 1;
-- Should return 0 rows
```

## Files Modified/Created

1. **Migrations**:
   - `2025-12-10-110610_CreateCoursesTable.php`
   - `2025-12-10-110557_CreateEnrollmentsTable.php`

2. **Models**:
   - `app/Models/CourseModel.php`
   - `app/Models/EnrollmentModel.php`

3. **Controllers**:
   - `app/Controllers/Course.php`

4. **Views**:
   - `app/Views/auth/dashboard.php` (updated student section)

5. **Routes**:
   - `app/Config/Routes.php` (added course/enroll route)

6. **Seeders**:
   - `app/Database/Seeds/CourseSeeder.php`

## Notes

- CSRF protection is enabled by default in CodeIgniter 4
- All user input is validated and sanitized
- Session-based authentication prevents unauthorized access
- Unique constraint on (user_id, course_id) prevents duplicate enrollments
- jQuery is used for AJAX functionality
- Bootstrap 5 provides UI components and alerts
