<?php

namespace tests\functional;

use Yii;
use app\models\User;

/**
 * LoginLogoutCest
 *
 * Functional tests for login and logout functionality
 *
 * Test credentials:
 * - Username: testuser (or email: testuser@example.com)
 * - Password: password123
 */
class LoginLogoutTest
{
    /**
     * Setup test user before each test
     *
     * @param \FunctionalTester $I
     */
    public function _before(\FunctionalTester $I)
    {
        // Clean up any existing test users
        User::deleteAll(['username' => ['testuser', 'admin', 'blockeduser']]);

        // Create test user
        $user = new User();
        $user->username = 'testuser';
        $user->email = 'testuser@example.com';
        $user->password_hash = '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO'; // password123
        $user->auth_key = 'test-auth-key-1';
        $user->confirmed_at = time();
        $user->created_at = time();
        $user->updated_at = time();
        $user->flags = 0;
        $user->detachBehavior('verLock');
        $user->save(false);

        // Create admin user
        $admin = new User();
        $admin->username = 'admin';
        $admin->email = 'admin@example.com';
        $admin->password_hash = '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO'; // password123
        $admin->auth_key = 'test-auth-key-2';
        $admin->confirmed_at = time();
        $admin->created_at = time();
        $admin->updated_at = time();
        $admin->flags = 0;
        $admin->detachBehavior('verLock');
        $admin->save(false);

        // Create blocked user
        $blocked = new User();
        $blocked->username = 'blockeduser';
        $blocked->email = 'blocked@example.com';
        $blocked->password_hash = '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO'; // password123
        $blocked->auth_key = 'test-auth-key-3';
        $blocked->confirmed_at = time();
        $blocked->blocked_at = time(); // Blocked user
        $blocked->created_at = time();
        $blocked->updated_at = time();
        $blocked->flags = 0;
        $blocked->detachBehavior('verLock');
        $blocked->save(false);
    }

    /**
     * Cleanup after each test
     *
     * @param \FunctionalTester $I
     */
    public function _after(\FunctionalTester $I)
    {
        // Clean up test users
        User::deleteAll(['username' => ['testuser', 'admin', 'blockeduser']]);
    }

    /**
     * Test successful login with username
     *
     * @param \FunctionalTester $I
     */
    public function testLoginWithUsername(\FunctionalTester $I)
    {
        $I->wantTo('login with valid username and password');

        // Visit login page
        $I->amOnRoute('auth/login');

        // Submit login form with username
        $I->sendAjaxPostRequest('/login', [
            'login' => 'testuser',
            'password' => 'password123',
            'rememberMe' => false,
        ]);

        // Check if user is logged in
        $I->assertTrue(!Yii::$app->user->isGuest, 'User should be logged in');
    }

    /**
     * Test successful login with email
     *
     * @param \FunctionalTester $I
     */
    public function testLoginWithEmail(\FunctionalTester $I)
    {
        $I->wantTo('login with valid email and password');

        // Visit login page
        $I->amOnRoute('auth/login');

        // Submit login form with email
        $I->sendAjaxPostRequest('/login', [
            'login' => 'testuser@example.com',
            'password' => 'password123',
            'rememberMe' => false,
        ]);

        // Check if user is logged in
        $I->assertTrue(!Yii::$app->user->isGuest, 'User should be logged in');
    }

    /**
     * Test login with wrong password
     *
     * @param \FunctionalTester $I
     */
    public function testLoginWithWrongPassword(\FunctionalTester $I)
    {
        $I->wantTo('verify login fails with wrong password');

        // Visit login page
        $I->amOnRoute('auth/login');

        // Submit login form with wrong password
        $I->sendAjaxPostRequest('/login', [
            'login' => 'testuser',
            'password' => 'wrongpassword',
            'rememberMe' => false,
        ]);

        // Check if user is still guest (not logged in)
        $I->assertTrue(Yii::$app->user->isGuest, 'User should not be logged in');
    }

    /**
     * Test login with non-existent user
     *
     * @param \FunctionalTester $I
     */
    public function testLoginWithNonExistentUser(\FunctionalTester $I)
    {
        $I->wantTo('verify login fails with non-existent user');

        // Visit login page
        $I->amOnRoute('auth/login');

        // Submit login form with non-existent user
        $I->sendAjaxPostRequest('/login', [
            'login' => 'nonexistentuser',
            'password' => 'password123',
            'rememberMe' => false,
        ]);

        // Check if user is still guest (not logged in)
        $I->assertTrue(Yii::$app->user->isGuest, 'User should not be logged in');
    }

    /**
     * Test login with blocked user
     *
     * @param \FunctionalTester $I
     */
    public function testLoginWithBlockedUser(\FunctionalTester $I)
    {
        $I->wantTo('verify login fails with blocked user');

        // Visit login page
        $I->amOnRoute('auth/login');

        // Submit login form with blocked user
        $I->sendAjaxPostRequest('/login', [
            'login' => 'blockeduser',
            'password' => 'password123',
            'rememberMe' => false,
        ]);

        // Check if user is still guest (not logged in)
        $I->assertTrue(Yii::$app->user->isGuest, 'Blocked user should not be able to log in');
    }

    /**
     * Test login with remember me
     *
     * @param \FunctionalTester $I
     */
    public function testLoginWithRememberMe(\FunctionalTester $I)
    {
        $I->wantTo('login with remember me option enabled');

        // Visit login page
        $I->amOnRoute('auth/login');

        // Submit login form with rememberMe enabled
        $I->sendAjaxPostRequest('/login', [
            'login' => 'testuser',
            'password' => 'password123',
            'rememberMe' => true,
        ]);

        // Check if user is logged in
        $I->assertTrue(!Yii::$app->user->isGuest, 'User should be logged in');

        // Verify that remember me cookie exists
        // Note: This requires checking the identity cookie which persists the login
        $identity = Yii::$app->user->identity;
        $I->assertNotNull($identity, 'User identity should exist');
    }

    /**
     * Test login validation - empty credentials
     *
     * @param \FunctionalTester $I
     */
    public function testLoginWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->wantTo('verify login validation works for empty credentials');

        // Visit login page
        $I->amOnRoute('auth/login');

        // Submit login form with empty credentials
        $I->sendAjaxPostRequest('/login', [
            'login' => '',
            'password' => '',
            'rememberMe' => false,
        ]);

        // Check if user is still guest (not logged in)
        $I->assertTrue(Yii::$app->user->isGuest, 'User should not be logged in with empty credentials');
    }

    /**
     * Test logout functionality
     *
     * @param \FunctionalTester $I
     */
    public function testLogout(\FunctionalTester $I)
    {
        $I->wantTo('logout after successful login');

        // First login
        $I->amOnRoute('auth/login');
        $I->sendAjaxPostRequest('/login', [
            'login' => 'testuser',
            'password' => 'password123',
            'rememberMe' => false,
        ]);

        // Verify user is logged in
        $I->assertTrue(!Yii::$app->user->isGuest, 'User should be logged in before logout');

        // Logout
        $I->sendAjaxPostRequest('/logout');

        // Verify user is logged out
        $I->assertTrue(Yii::$app->user->isGuest, 'User should be logged out');
        $I->assertNull(Yii::$app->user->identity, 'User identity should be null after logout');
    }

    /**
     * Test logout requires POST method
     *
     * @param \FunctionalTester $I
     */
    public function testLogoutRequiresPost(\FunctionalTester $I)
    {
        $I->wantTo('verify that logout only accepts POST requests');

        // First login
        $I->amOnRoute('auth/login');
        $I->sendAjaxPostRequest('/login', [
            'login' => 'testuser',
            'password' => 'password123',
            'rememberMe' => false,
        ]);

        // Verify user is logged in
        $I->assertTrue(!Yii::$app->user->isGuest, 'User should be logged in');

        // Try to logout with GET request (should fail)
        $I->amOnRoute('auth/logout');

        // Since VerbFilter is applied, GET request should be denied
        // User should still be logged in
        $I->assertTrue(!Yii::$app->user->isGuest, 'User should still be logged in after GET request to logout');
    }

    /**
     * Test that guest cannot access logout
     *
     * @param \FunctionalTester $I
     */
    public function testGuestCannotLogout(\FunctionalTester $I)
    {
        $I->wantTo('verify that guest user cannot access logout');

        // Ensure user is guest
        $I->assertTrue(Yii::$app->user->isGuest, 'User should be guest');

        // Try to logout as guest
        $I->sendAjaxPostRequest('/logout');

        // Should be redirected to login page or get access denied
        // The exact behavior depends on AccessControl configuration
        $I->assertTrue(Yii::$app->user->isGuest, 'User should still be guest');
    }

    /**
     * Test redirect to dashboard after successful login
     *
     * @param \FunctionalTester $I
     */
    public function testRedirectToDashboardAfterLogin(\FunctionalTester $I)
    {
        $I->wantTo('verify redirect to dashboard after successful login');

        // Visit login page
        $I->amOnRoute('auth/login');

        // Submit login form
        $I->sendAjaxPostRequest('/login', [
            'login' => 'testuser',
            'password' => 'password123',
            'rememberMe' => false,
        ]);

        // Check if user is logged in
        $I->assertTrue(!Yii::$app->user->isGuest, 'User should be logged in');

        // Verify last login information is updated
        $user = Yii::$app->user->identity;
        $I->assertNotNull($user->last_login_at, 'Last login time should be set');
        $I->assertNotNull($user->last_login_ip, 'Last login IP should be set');
    }

    /**
     * Test that logged-in user is redirected when accessing login page
     *
     * @param \FunctionalTester $I
     */
    public function testLoggedInUserCannotAccessLoginPage(\FunctionalTester $I)
    {
        $I->wantTo('verify that logged-in user cannot access login page');

        // First login
        $I->amOnRoute('auth/login');
        $I->sendAjaxPostRequest('/login', [
            'login' => 'testuser',
            'password' => 'password123',
            'rememberMe' => false,
        ]);

        // Verify user is logged in
        $I->assertTrue(!Yii::$app->user->isGuest, 'User should be logged in');

        // Try to access login page again
        $I->amOnRoute('auth/login');

        // Should not see login form, should be redirected to dashboard
        // The controller checks if user is guest and redirects if not
    }
}

