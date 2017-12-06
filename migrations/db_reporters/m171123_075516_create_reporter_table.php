<?php

use yii\db\Migration;

/**
 * Handles the creation of table `reporter`.
 */
class m171123_075516_create_reporter_table extends Migration
{
    public $db = 'db_reporter';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('reporter', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->unsigned(),
            'birthday' => $this->integer()->unsigned(),
            'full_name' => $this->string(128),
            'short_name' => $this->string(64),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('reporter');
    }

    protected function getDb()
    {
        return Yii::$app->get('db_reporter');
    }
}
