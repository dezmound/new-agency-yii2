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
            'token' => $this->string(256),
            'auth_id' => $this->integer()->notNull(),
            'expire_at' => $this->integer()->unsigned()
        ]);
        $this->addPrimaryKey('token', 'token','token');
        $this->execute("
        CREATE EVENT tokens_expire_event
    ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 MINUTE
    DO
      DELETE FROM `token` WHERE `expire_at` < CURRENT_TIMESTAMP();
        ");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('token');
    }
}
