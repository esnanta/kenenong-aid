<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%disaster}}`.
 */
class m251215_094135_create_disaster_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%disaster}}', [
            'id' => $this->primaryKey(),
            'disaster_type' => $this->integer()->notNull()->comment('1=Earthquake, 2=Flood, 3=Fire, 4=Tsunami, 5=Volcano, 6=Landslide, 99=Other'),
            'disaster_status' => $this->integer()->notNull()->comment('1=Active, 2=Resolved, 3=Monitoring'),
            'start_date' => $this->date()->notNull(),
            'end_date' => $this->date(),
            'description' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'is_deleted' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->timestamp()->null(),
            'deleted_by' => $this->integer()->null(),
            'verlock' => $this->integer()->defaultValue(0),
            'uuid' => $this->string(36)->unique(),
        ]);

        // Create indexes
        $this->createIndex('idx-disaster-disaster_type', '{{%disaster}}', 'disaster_type');
        $this->createIndex('idx-disaster-disaster_status', '{{%disaster}}', 'disaster_status');
        $this->createIndex('idx-disaster-start_date', '{{%disaster}}', 'start_date');
        $this->createIndex('idx-disaster-is_deleted', '{{%disaster}}', 'is_deleted');

        // Add foreign keys if users table exists
        $this->addForeignKey('fk-disaster-created_by', '{{%disaster}}', 'created_by', '{{%users}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-disaster-updated_by', '{{%disaster}}', 'updated_by', '{{%users}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-disaster-deleted_by', '{{%disaster}}', 'deleted_by', '{{%users}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign keys
        $this->dropForeignKey('fk-disaster-created_by', '{{%disaster}}');
        $this->dropForeignKey('fk-disaster-updated_by', '{{%disaster}}');
        $this->dropForeignKey('fk-disaster-deleted_by', '{{%disaster}}');

        // Drop indexes
        $this->dropIndex('idx-disaster-disaster_type', '{{%disaster}}');
        $this->dropIndex('idx-disaster-disaster_status', '{{%disaster}}');
        $this->dropIndex('idx-disaster-start_date', '{{%disaster}}');
        $this->dropIndex('idx-disaster-is_deleted', '{{%disaster}}');

        // Drop table
        $this->dropTable('{{%disaster}}');
    }
}
