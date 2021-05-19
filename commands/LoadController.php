<?php

namespace app\commands;

use app\Service\CurrencyService;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\httpclient\XmlParser;
use yii\mutex\FileMutex;

final class LoadController extends Controller
{
    private const MUTEX_NAME = 'currency_load_command';

    public function actionIndex(): int
    {
        $mutex = new FileMutex();
        if (!$mutex->acquire(self::MUTEX_NAME, 0)) {
            echo "Процесс уже запущен.\n";
        }

        $currencyService = Yii::$container->get(CurrencyService::class);
        $currencyDataFile = $currencyService->getData();
        if (null === $currencyDataFile) {
            $mutex->release(self::MUTEX_NAME);
            return ExitCode::DATAERR;
        }

        $parser = new XmlParser();
        $data = $parser->parse($currencyDataFile);
        $currencyService->saveData($data);

        $mutex->release(self::MUTEX_NAME);
        return ExitCode::OK;
    }
}
