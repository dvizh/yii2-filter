<?php

use yii\db\Migration;

class m170628_150322_is_option_field extends Migration {

    public function safeUp() {
        try {
            $this->addColumn('{{%filter}}', 'is_option', "ENUM('yes', 'no') NULL DEFAULT  'no'");
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' ';
        }
    }

    public function safeDown() {
        try {
            $this->dropColumn('{{%filter}}', 'is_option');
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' ';
        }
    }

}
