<?php

namespace app\Service;

use app\Repository\CurrencyRepository;
use Exception;
use RuntimeException;
use Yii;
use yii\httpclient\Client;
use yii\httpclient\Response;

final class CurrencyService
{
    public function convertRate(string $rate): float
    {
        $fixDelimiter = str_replace(',', '.', $rate);
        return (float)$fixDelimiter;
    }

    public function getData(): ?Response
    {
        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl(Yii::$app->params['currencyDataUrl'])
                ->send();

            if ($response->isOk) {
                return $response;
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return null;
    }

    public function saveData(array $data): void
    {
        if (!array_key_exists('Valute', $data)) {
            throw new RuntimeException('Данные о валютах отсутствуют.');
        }
        $currencyRepository = Yii::$container->get(CurrencyRepository::class);

        $list = $data['Valute'];
        foreach ($list as $options) {
            try {
                $rate = $this->convertRate($options['Value']);
                $currencyRepository->getOrCreate(
                    $options['Name'],
                    $rate,
                    $options['NumCode'],
                    $options['CharCode'],
                    $options['@attributes']['ID']
                );
            } catch (Exception $exception) {
                echo sprintf("Ошибка обновления %s, %s.",
                    $options['Name'],
                    $exception->getMessage()
                );
            }
        }
    }
}
