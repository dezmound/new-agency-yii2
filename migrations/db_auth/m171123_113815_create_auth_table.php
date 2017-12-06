<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth`.
 */
class m171123_113815_create_auth_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->unsigned(),
            'login' => $this->string(64)->notNull(),
            'password' => $this->string(128)->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('auth');
    }
}
