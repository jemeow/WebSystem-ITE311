<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Test route to view users
$routes->get('test-users', 'TestUsers::index');

// Authentication routes
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::processRegister');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');
$routes->get('logout', 'Auth::logout');

// Protected routes - require authentication
$routes->get('dashboard', 'Auth::dashboard', ['filter' => 'auth']);

$routes->get('courses', 'Course::index', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'courses/search', 'Course::search', ['filter' => 'auth']);

// Profile routes - available to all authenticated users
$routes->group('profile', ['filter' => 'auth'], function($routes) {
    $routes->get('edit', 'Profile::edit');
    $routes->post('update', 'Profile::update');
});

// Course enrollment routes - available to authenticated users
$routes->group('course', ['filter' => 'auth'], function($routes) {
    $routes->post('enroll', 'Course::enroll');
    $routes->post('unenroll', 'Course::unenroll');
    $routes->get('check-status', 'Course::checkStatus');
    $routes->match(['get', 'post'], 'search', 'Course::search');
});

// Teacher routes
$routes->group('teacher', ['filter' => 'auth'], function($routes) {
    // Course management
    $routes->get('courses', 'Teacher::courses');
    $routes->get('course/(:num)', 'Teacher::viewCourse/$1');
    $routes->post('course/(:num)/upload', 'Materials::upload/$1');
    
    // Enrollment management
    $routes->get('enrollments', 'TeacherEnrollment::index');
    $routes->get('enrollments/pending', 'TeacherEnrollment::getPendingEnrollments');
    $routes->post('enrollments/approve', 'TeacherEnrollment::approveEnrollment');
    $routes->post('enrollments/reject', 'TeacherEnrollment::rejectEnrollment');
    $routes->get('enrollments/history', 'TeacherEnrollment::history');
    
    // Teacher student enrollment management
    $routes->get('manage-students', 'TeacherEnrollment::manageStudents');
    $routes->post('enrollments/get-student-enrollments', 'TeacherEnrollment::getStudentEnrollments');
    $routes->post('enrollments/enroll-student', 'TeacherEnrollment::enrollStudent');
    $routes->post('enrollments/unenroll-student', 'TeacherEnrollment::unenrollStudent');
});

// Student routes
$routes->group('student', ['filter' => 'auth'], function($routes) {
    $routes->get('courses', 'Student::courses');
});

// Admin-only routes
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('users', 'UserManagement::index');
    $routes->get('users/create', 'UserManagement::create');
    $routes->post('users/store', 'UserManagement::store');
    $routes->get('users/edit/(:num)', 'UserManagement::edit/$1');
    $routes->post('users/update/(:num)', 'UserManagement::update/$1');
    $routes->post('users/deactivate/(:num)', 'UserManagement::deactivate/$1');
    $routes->post('users/activate/(:num)', 'UserManagement::activate/$1');
    $routes->post('users/delete/(:num)', 'UserManagement::permanentDelete/$1');
    
    // Admin course management routes
    $routes->get('courses', 'CourseManagement::index');
    $routes->get('courses/assign-teachers', 'CourseManagement::assignTeachers');
    $routes->get('courses/create', 'CourseManagement::create');
    $routes->post('courses/store', 'CourseManagement::store');
    $routes->get('courses/edit/(:num)', 'CourseManagement::edit/$1');
    $routes->post('courses/update/(:num)', 'CourseManagement::update/$1');
    $routes->post('courses/delete/(:num)', 'CourseManagement::delete/$1');
    $routes->post('courses/toggle-status/(:num)', 'CourseManagement::toggleStatus/$1');
    $routes->post('courses/assign-teacher', 'CourseManagement::assignTeacher');
    
    // Admin course detail and materials routes
    $routes->get('course/(:num)', 'Admin::viewCourse/$1');
    $routes->post('course/(:num)', 'Materials::upload/$1');
    
    // Admin enrollment management routes
    $routes->get('enrollments/dashboard', 'AdminEnrollment::index');
    $routes->get('enrollments', 'AdminEnrollment::index');
    $routes->get('enrollments/pending-view', 'AdminEnrollment::pending');
    $routes->get('enrollments/pending', 'AdminEnrollment::getPendingEnrollments');
    $routes->get('enrollments/history', 'AdminEnrollment::history');
    $routes->post('enrollments/approve', 'AdminEnrollment::approveEnrollment');
    $routes->post('enrollments/reject', 'AdminEnrollment::rejectEnrollment');
    $routes->post('enrollments/get-student-enrollments', 'AdminEnrollment::getStudentEnrollments');
    $routes->post('enrollments/unenroll-student', 'AdminEnrollment::unenrollStudent');
});

// Materials routes - available to authenticated users
$routes->group('materials', ['filter' => 'auth'], function($routes) {
    $routes->get('delete/(:num)', 'Materials::delete/$1');
    $routes->get('download/(:num)', 'Materials::download/$1');
    $routes->get('course/(:num)', 'Materials::viewCourseMaterials/$1');
    $routes->post('enrollments/enroll-student', 'AdminEnrollment::enrollStudent');
    $routes->post('enrollments/unenroll-student', 'AdminEnrollment::unenrollStudent');
});

// Notification routes - available to authenticated users
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('notifications', 'Notifications::get');
    $routes->get('notifications/stream', 'Notifications::stream');
    $routes->post('notifications/mark_read/(:num)', 'Notifications::mark_as_read/$1');
});
