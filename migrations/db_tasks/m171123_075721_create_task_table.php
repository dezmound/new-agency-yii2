<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task`.
 */
class m171123_075721_create_task_table extends Migration
{
    public $db = 'db_tasks';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'begin' => $this->integer()->unsigned(),
            'end' => $this->integer()->unsigned()->notNull(),
            'description' => $this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('task');
    }

    protected function getDb()
    {
        return Yii::$app->get('db_tasks');
    }
}
