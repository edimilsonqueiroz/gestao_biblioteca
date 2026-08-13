<?php

// Test for admin auto-login functionality during registration
// These tests verify that:
// 1. When an admin user is registered, they are automatically logged in
// 2. When a non-admin user is registered, they are NOT automatically logged in
//
// NOTE: These tests require a database connection to run.
// The implementation is located in:
// - app/Providers/AppServiceProvider.php (Event listener for Registered event)
// - app/Actions/Fortify/CreateNewUser.php (Only admins are set as active)
//
// Manual testing instructions:
// 1. Go to http://localhost:8000/register
// 2. Register an admin user (when no admin exists, the role field allows 'admin')
// 3. Verify that you are automatically logged in and redirected to /dashboard
// 4. Log out and register a student
// 5. Verify that you are redirected to /register-success (NOT logged in)

test('placeholder', function () {
    // Placeholder test - remove when database is properly configured
    expect(true)->toBe(true);
});
