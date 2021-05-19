<?php

namespace app\models;

use yii\db\ActiveRecord;

final class Currency extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'currency';
    }
    public function fields(): array
    {
        return ['id', 'name', 'rate'];
    }

    public function extraFields(): array
    {
        return ['num_code', 'char_code', 'valute_id'];
    }

    public function rules(): array
    {
        return [
            [['name', 'num_code', 'char_code', 'valute_id'], 'required'],
            [['rate'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['char_code', 'num_code'], 'string', 'max' => 4],
            [['valute_id'], 'string', 'max' => 12],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'rate' => 'Rate',
            'num_code' => 'Num Code',
            'char_code' => 'Char Code',
            'valute_id' => 'Valute ID',
        ];
    }
}
