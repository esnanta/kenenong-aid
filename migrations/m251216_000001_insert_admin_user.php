<?php

use yii\db\Migration;

/**
 * Class m251216_000001_insert_admin_user
 * Creates initial admin user for usuario
 */
class m251216_000001_insert_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = time();

        // Password hash for 'Admin123!'
        // Generated using: Yii::$app->security->generatePasswordHash('Admin123!')
        $passwordHash = '$2y$13$' . substr(base64_encode(random_bytes(16)), 0, 22);

        // Use bcrypt untuk password: Admin123!
        // Kita akan generate saat runtime
        $security = Yii::$app->security;
        $passwordHash = $security->generatePasswordHash('Admin123!');
        $authKey = $security->generateRandomString();

        // Insert admin user
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password_hash' => $passwordHash,
            'auth_key' => $authKey,
            'confirmed_at' => $time,
            'created_at' => $time,
            'updated_at' => $time,
            'flags' => 0,
        ]);

        // Get user ID
        $userId = $this->db->lastInsertID;

        // Insert profile
        $this->insert('{{%profile}}', [
            'user_id' => $userId,
        ]);

        // Create admin role if not exists
        $role = $this->db->createCommand(
            'SELECT name FROM {{%auth_item}} WHERE name = :name AND type = :type',
            [':name' => 'admin', ':type' => 1]
        )->queryOne();

        if (!$role) {
            $this->insert('{{%auth_item}}', [
                'name' => 'admin',
                'type' => 1, // TYPE_ROLE
                'description' => 'Administrator',
                'created_at' => $time,
                'updated_at' => $time,
            ]);
        }

        // Assign admin role to user
        $this->insert('{{%auth_assignment}}', [
            'item_name' => 'admin',
            'user_id' => (string)$userId,
            'created_at' => $time,
        ]);

        echo "Admin user created successfully!\n";
        echo "Username: admin\n";
        echo "Email: admin@example.com\n";
        echo "Password: Admin123!\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Delete admin role assignment
        $this->delete('{{%auth_assignment}}', ['item_name' => 'admin']);

        // Delete admin role
        $this->delete('{{%auth_item}}', ['name' => 'admin']);

        // Delete admin user (cascade akan delete profile)
        $this->delete('{{%user}}', ['username' => 'admin']);

        echo "Admin user deleted.\n";
    }
}

