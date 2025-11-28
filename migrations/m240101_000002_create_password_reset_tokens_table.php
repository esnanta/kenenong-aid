<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%password_reset_tokens}}`.
 */
class m240101_000002_create_password_reset_tokens_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%password_reset_tokens}}', [
            'email' => $this->string(255)->notNull()->append('PRIMARY KEY'),
            'token' => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%password_reset_tokens}}');
    }
}


