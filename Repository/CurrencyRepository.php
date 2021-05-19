<?php

namespace app\Repository;

use app\models\Currency;
use Exception;

class CurrencyRepository
{
    public function getOrCreate(
        string $name,
        float $rate,
        string $numCode,
        string $charCode,
        string $valuteId
    ): Currency
    {
        $model = $this->get($numCode);
        if (null === $model) {
            $model = $this->create($name, $rate, $numCode, $charCode, $valuteId);
        }

        return $model;
    }

    public function create(
        string $name,
        float $rate,
        string $numCode,
        string $charCode,
        string $valuteId
    ): Currency
    {
        $model = new Currency([
            'name' => $name,
            'rate' => $rate,
            'num_code' => $numCode,
            'char_code' => $charCode,
            'valute_id' => $valuteId,
        ]);

        if ($model->validate()) {
            $model->save(false);
        } else {
            throw new Exception(sprintf("Ошибка обновления %s, %s.",
                $name,
                implode(', ', $model->errors)
            ));
        }
    }

    public function get(string $numCode): ?Currency
    {
        return Currency::findOne(['num_code' => $numCode]);
    }
}
