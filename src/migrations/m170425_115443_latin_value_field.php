<?php

use yii\db\Migration;

class m170425_115443_latin_value_field extends Migration {

    public function safeUp() {
        try {
            $this->addColumn('{{%filter_variant}}', 'latin_value', $this->string(255));
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' ';
        }
    }

    public function safeDown() {
        try {
            $this->dropColumn('{{%filter_variant}}', 'latin_value');
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' ';
        }
    }

}
