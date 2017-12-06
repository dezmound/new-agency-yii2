<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m171123_114141_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64),
            'email' => $this->string(64),
            'image' => $this->binary(),
            'birthday' => $this->integer()->unsigned(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
