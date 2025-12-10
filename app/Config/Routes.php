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

// Profile routes - available to all authenticated users
$routes->group('profile', ['filter' => 'auth'], function($routes) {
    $routes->get('edit', 'Profile::edit');
    $routes->post('update', 'Profile::update');
});

// Course enrollment routes - available to authenticated users
$routes->group('course', ['filter' => 'auth'], function($routes) {
    $routes->post('enroll', 'Course::enroll');
    $routes->post('unenroll', 'Course::unenroll');
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
    
    // Admin enrollment management routes
    $routes->get('enrollments/dashboard', 'AdminEnrollment::dashboard');
    $routes->get('enrollments', 'AdminEnrollment::index');
    $routes->post('enrollments/get-student-enrollments', 'AdminEnrollment::getStudentEnrollments');
    $routes->post('enrollments/enroll-student', 'AdminEnrollment::enrollStudent');
    $routes->post('enrollments/unenroll-student', 'AdminEnrollment::unenrollStudent');
});
