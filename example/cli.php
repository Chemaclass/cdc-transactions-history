#!/usr/local/bin/php
<?php

declare(strict_types=1);

use App\CroExcelHistory\CroExcelHistoryFacade;
use App\CroExcelHistory\Domain\Service\CsvReaderService;

require dirname(__DIR__) . '/vendor/autoload.php';

$inputFileName = $argv[1] ?? 'data/transactions.csv';

$csv = (new CsvReaderService())->read($inputFileName);

$facade = new CroExcelHistoryFacade();
$actual = $facade->statisticsByKind($csv);

dump($actual);
