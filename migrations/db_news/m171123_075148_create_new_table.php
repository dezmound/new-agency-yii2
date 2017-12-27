<?php

use yii\db\Migration;

/**
 * Handles the creation of table `new`.
 */
class m171123_075148_create_new_table extends Migration
{
    public $db = 'db_news';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('new', [
            'id' => $this->primaryKey(),
            'date_create' => $this->integer()->unsigned(),
            'date_update' => $this->integer()->unsigned(),
            'short' => $this->string(256),
            'full' => $this->text(),
            'theme' => $this->string(64)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('new');
    }

    protected function getDb()
    {
        return Yii::$app->get('db_news');
    }
}
