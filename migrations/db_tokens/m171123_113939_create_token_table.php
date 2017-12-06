<?php

use yii\db\Migration;

/**
 * Handles the creation of table `token`.
 */
class m171123_113939_create_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('token', [
            'token' => $this->string(128),
            'auth_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('token', 'token','token');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('token');
    }
}
