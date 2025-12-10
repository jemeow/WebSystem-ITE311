# RBAC Testing Guide - ITE311-ZURI

## Quick Start Testing

### 1. View All Users in Database
**URL:** http://localhost:8080/test-users

This page shows all users with their roles. Use this to verify:
- ✅ Users exist in the database
- ✅ Roles are correctly set (admin, teacher, student)
- ✅ Passwords are properly hashed

### 2. Test Login System

#### Test Admin Account
1. Go to: http://localhost:8080/login
2. Login with:
   - **Email:** jesse@gmail.com
   - **Password:** admin123
3. **Expected Result:**
   - Redirects to `/dashboard`
   - Shows "Admin" badge (red)
   - Displays 4 statistics cards (Total Users, Admins, Teachers, Students)
   - Shows "Recent Users" table
   - Sidebar shows admin menu items:
     - ✓ Manage Users
     - ✓ Manage Courses
     - ✓ System Settings
     - ✓ Reports

#### Test Teacher Account
1. Logout (if logged in)
2. Go to: http://localhost:8080/login
3. Login with:
   - **Email:** ogillee@gmail.com
   - **Password:** teacher123
4. **Expected Result:**
   - Redirects to `/dashboard`
   - Shows "Teacher" badge (yellow/warning)
   - Displays 3 statistics cards (My Courses, Total Students, Assignments)
   - Shows "Quick Actions" and "Recent Activity" sections
   - Sidebar shows teacher menu items:
     - ✓ My Courses
     - ✓ Create Course
     - ✓ My Students
     - ✓ Assignments
     - ✓ Grades

#### Test Student Account
1. Logout (if logged in)
2. Go to: http://localhost:8080/login
3. Login with:
   - **Email:** tally@gmail.com
   - **Password:** student123
4. **Expected Result:**
   - Redirects to `/dashboard`
   - Shows "Student" badge (green)
   - Displays 3 statistics cards (Enrolled Courses, Completed Lessons, Pending Assignments)
   - Shows "My Courses" and "Upcoming Assignments" sections
   - Sidebar shows student menu items:
     - ✓ My Courses
     - ✓ Browse Courses
     - ✓ Assignments
     - ✓ My Grades

### 3. Test Registration System

1. Go to: http://localhost:8080/register
2. Fill in:
   - **Name:** Test Student
   - **Email:** teststudent@gmail.com
   - **Password:** password123
   - **Confirm Password:** password123
3. Click "Register"
4. **Expected Result:**
   - Redirects to login page
   - Shows success message
   - New user created with 'student' role (default)
5. Login with the new account to verify it works

### 4. Test Authentication Filter

1. **While logged OUT**, try to access: http://localhost:8080/dashboard
2. **Expected Result:**
   - Redirects to `/login`
   - Shows error message: "Please login to access this page"

### 5. Test Dynamic Navigation

Compare the navigation bar for each role:

**When NOT logged in:**
- Shows: Home, About, Contact, Login, Register

**Admin Navigation:**
- Shows: Home, Dashboard, Users, Courses, Settings
- User dropdown with role badge (red)

**Teacher Navigation:**
- Shows: Home, Dashboard, My Courses, Students, Assignments
- User dropdown with role badge (yellow)

**Student Navigation:**
- Shows: Home, Dashboard, My Courses, Browse Courses, My Grades
- User dropdown with role badge (green)

### 6. Test Logout Functionality

1. While logged in, click "Logout" from dropdown menu
2. **Expected Result:**
   - Redirects to home page
   - Shows success message
   - Cannot access `/dashboard` anymore
   - Navigation bar shows public links only

## Security Tests

### ✅ Password Hashing
- Check database directly - passwords should NOT be plain text
- Should see bcrypt hashes like: `$2y$10$...`

### ✅ Session Management
- Login creates session
- Logout destroys session
- Session contains: id, name, email, role, isLoggedIn

### ✅ CSRF Protection
- Forms include CSRF tokens
- POST requests validate tokens

### ✅ XSS Protection
- All output uses `esc()` function
- User input is sanitized

### ✅ SQL Injection Protection
- Uses Query Builder and prepared statements
- No raw SQL with user input

### ✅ Authorization
- Protected routes use 'auth' filter
- Redirects unauthorized users
- Role-based content rendering

## Screenshot Checklist for Submission

### Screenshot 1: Users Table ✅
**URL:** http://localhost:8080/test-users
**Capture:** Full page showing all users with different roles

### Screenshot 2: Admin Dashboard ✅
**Steps:**
1. Login as: jesse@gmail.com / admin123
2. **URL:** http://localhost:8080/dashboard
3. **Capture:** Full dashboard showing:
   - Statistics cards (Total Users, Admins, Teachers, Students)
   - Recent Users table
   - Admin sidebar menu

### Screenshot 3: Teacher Dashboard ✅
**Steps:**
1. Logout and login as: ogillee@gmail.com / teacher123
2. **URL:** http://localhost:8080/dashboard
3. **Capture:** Full dashboard showing:
   - Teacher statistics
   - Quick Actions section
   - Teacher sidebar menu

### Screenshot 4: Student Dashboard ✅
**Steps:**
1. Logout and login as: tally@gmail.com / student123
2. **URL:** http://localhost:8080/dashboard
3. **Capture:** Full dashboard showing:
   - Student statistics
   - My Courses section
   - Student sidebar menu

### Screenshot 5: Navigation Comparison ✅
**Option A:** Side-by-side comparison
- Take screenshot of admin navigation
- Take screenshot of student navigation
- Combine in image editor

**Option B:** Browser tab comparison
- Open two browser windows
- Login as admin in one, student in other
- Capture both showing different menu items

### Screenshot 6: GitHub Commits ✅
**URL:** https://github.com/jemeow/WebSystem-ITE311/commits/master
**Capture:** Commit history showing:
- Multiple commits (at least 5)
- Descriptive commit messages
- Dates spanning 4+ days before submission
- "ROLE BASE Implementation" commit visible

## Common Issues & Solutions

### Issue: "Email not found in database"
**Solution:** Run seeder: `php spark db:seed UserSeeder`

### Issue: "Invalid email or password"
**Solutions:**
- Verify password is correct (admin123, teacher123, student123)
- Check if users exist: http://localhost:8080/test-users
- Ensure migrations ran: `php spark migrate`

### Issue: Can't access dashboard
**Solutions:**
- Make sure you're logged in
- Clear browser cache/cookies
- Check session is active

### Issue: Wrong role displayed
**Solutions:**
- Check database role values
- Verify migration ran: `php spark migrate:status`
- Run role update migration

### Issue: Page not found
**Solutions:**
- Verify server is running: `php spark serve`
- Check Routes.php configuration
- Clear route cache: `php spark cache:clear`

## GitHub Commit Strategy

To show version control progress (4+ days), make these commits:

### Day 1: Initial Setup
```bash
git add app/Filters/AuthFilter.php app/Config/Filters.php
git commit -m "Add authentication filter for route protection"
git push origin master
```

### Day 2: Controller Updates
```bash
git add app/Controllers/Auth.php app/Config/Routes.php
git commit -m "Enhance login process and dashboard with role-based logic"
git push origin master
```

### Day 3: Views and Templates
```bash
git add app/Views/auth/dashboard.php app/Views/templates/
git commit -m "Create unified dashboard and dynamic navigation templates"
git push origin master
```

### Day 4: Database and Testing
```bash
git add app/Database/
git commit -m "Update migrations and seeders for role-based system"
git push origin master
```

### Final Day: Documentation
```bash
git add .
git commit -m "ROLE BASE Implementation - Complete with testing and documentation"
git push origin master
```

## Verification Checklist

Before submitting, verify:

- [ ] All 3 user roles can login successfully
- [ ] Each role sees different dashboard content
- [ ] Navigation bar changes based on role
- [ ] Can register new student accounts
- [ ] Cannot access dashboard without login
- [ ] Logout works and destroys session
- [ ] All 6 screenshots captured
- [ ] At least 5 commits over 4+ days
- [ ] Code pushed to GitHub
- [ ] README or summary document updated

## Next Steps After Testing

1. ✅ Test all functionality
2. ✅ Capture all 6 required screenshots
3. ✅ Make multiple commits over several days
4. ✅ Push final code to GitHub
5. ✅ Prepare submission documentation
6. ✅ Submit assignment

---

## Quick Test Commands

```bash
# Start server
php spark serve

# View migrations
php spark migrate:status

# Run migrations
php spark migrate

# Seed database
php spark db:seed UserSeeder

# Clear cache
php spark cache:clear
```

## Important URLs

- **Home:** http://localhost:8080/
- **Login:** http://localhost:8080/login
- **Register:** http://localhost:8080/register
- **Dashboard:** http://localhost:8080/dashboard
- **Test Users:** http://localhost:8080/test-users

---

**Status:** ✅ ALL FEATURES IMPLEMENTED AND READY FOR TESTING
**Server:** http://localhost:8080
**Current Time:** December 9, 2025
