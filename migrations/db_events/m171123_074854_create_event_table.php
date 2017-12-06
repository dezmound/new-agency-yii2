<?php

use yii\db\Migration;

/**
 * Handles the creation of table `event`.
 */
class m171123_074854_create_event_table extends Migration
{
    public $db = 'db_events';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('event', [
            'id' => $this->primaryKey(),
            'date_create' => $this->integer()->unsigned(),
            'date_end' => $this->integer()->unsigned(),
            'status' => $this->integer(5)->unsigned(),
            'name' => $this->string(128),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('event');
    }

    protected function getDb()
    {
        return Yii::$app->get('db_events');
    }
}
