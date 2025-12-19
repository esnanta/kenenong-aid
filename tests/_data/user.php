<?php

/**
 * User fixture data
 *
 * This fixture provides test user data for authentication tests
 * Password for all test users: 'password123'
 */
return [
    'user1' => [
        'id' => 1,
        'username' => 'testuser',
        'email' => 'testuser@example.com',
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO', // password123
        'auth_key' => 'test-auth-key-1',
        'confirmed_at' => time(),
        'unconfirmed_email' => null,
        'blocked_at' => null,
        'registration_ip' => '127.0.0.1',
        'flags' => 0,
        'last_login_at' => null,
        'last_login_ip' => null,
        'created_at' => time(),
        'updated_at' => time(),
        'password_changed_at' => null,
        'auth_tf_key' => null,
        'auth_tf_enabled' => '0',
        'auth_tf_type' => null,
        'auth_tf_mobile_phone' => null,
        'gdpr_consent' => null,
        'gdpr_consent_date' => null,
        'gdpr_deleted' => '0',
        'uuid' => null,
        'verlock' => 0,
        'lock' => 0,
    ],
    'user2' => [
        'id' => 2,
        'username' => 'admin',
        'email' => 'admin@example.com',
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO', // password123
        'auth_key' => 'test-auth-key-2',
        'confirmed_at' => time(),
        'unconfirmed_email' => null,
        'blocked_at' => null,
        'registration_ip' => '127.0.0.1',
        'flags' => 0,
        'last_login_at' => null,
        'last_login_ip' => null,
        'created_at' => time(),
        'updated_at' => time(),
        'password_changed_at' => null,
        'auth_tf_key' => null,
        'auth_tf_enabled' => '0',
        'auth_tf_type' => null,
        'auth_tf_mobile_phone' => null,
        'gdpr_consent' => null,
        'gdpr_consent_date' => null,
        'gdpr_deleted' => '0',
        'uuid' => null,
        'verlock' => 0,
        'lock' => 0,
    ],
    'blockedUser' => [
        'id' => 3,
        'username' => 'blockeduser',
        'email' => 'blocked@example.com',
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO', // password123
        'auth_key' => 'test-auth-key-3',
        'confirmed_at' => time(),
        'unconfirmed_email' => null,
        'blocked_at' => time(), // This user is blocked
        'registration_ip' => '127.0.0.1',
        'flags' => 0,
        'last_login_at' => null,
        'last_login_ip' => null,
        'created_at' => time(),
        'updated_at' => time(),
        'password_changed_at' => null,
        'auth_tf_key' => null,
        'auth_tf_enabled' => '0',
        'auth_tf_type' => null,
        'auth_tf_mobile_phone' => null,
        'gdpr_consent' => null,
        'gdpr_consent_date' => null,
        'gdpr_deleted' => '0',
        'uuid' => null,
        'verlock' => 0,
        'lock' => 0,
    ],
];

