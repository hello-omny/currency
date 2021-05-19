<?php

use yii\db\Migration;

final class m210519_080906_init extends Migration
{
    private const TABLE_NAME = 'currency';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'rate' => $this->decimal(10,4)->notNull()->defaultValue(0),
            'num_code' => $this->string(4)->notNull(),
            'char_code' => $this->string(4)->notNull(),
            'valute_id' => $this->string(12)->notNull(),
        ]);
        $this->createIndex($this->genIndexName('num_code'), self::TABLE_NAME, 'num_code');
        $this->createIndex($this->genIndexName('valute_id'), self::TABLE_NAME, 'valute_id');
    }

    public function safeDown(): void
    {
        $this->dropTable(self::TABLE_NAME);
    }

    private function genIndexName(string $name): string
    {
        return sprintf('idx__%s__%s', self::TABLE_NAME, $name);
    }
}
