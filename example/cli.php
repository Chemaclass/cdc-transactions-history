#!/usr/local/bin/php
<?php

declare(strict_types=1);

use App\CroExcelHistory\CroExcelHistoryFacade;
use App\Csv\CsvReader;

require dirname(__DIR__) . '/vendor/autoload.php';

$inputFileName = $argv[1] ?? 'data/transactions.csv';

$csv = (new CsvReader())->read($inputFileName);

$facade = new CroExcelHistoryFacade();
$actual = $facade->statisticsByKind($csv);

dump($actual);
