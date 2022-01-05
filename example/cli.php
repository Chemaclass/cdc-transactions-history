#!/usr/local/bin/php
<?php

declare(strict_types=1);

use App\Csv\CsvReader;
use App\Domain\GroupedTransactions;
use App\Domain\Transaction;
use App\Domain\TransactionKind;
use App\Domain\TransactionManager\VIbanPurchaseTransactionManager;
use App\Domain\TransactionStatistics;

require dirname(__DIR__) . '/vendor/autoload.php';

$inputFileName = $argv[1] ?? 'data/transactions.csv';

$csv = (new CsvReader())->read($inputFileName);

$transactions = array_map(static fn (array $i) => Transaction::fromArray($i), $csv);
$grouped = (new GroupedTransactions())->byKind(...$transactions);

$stats = new TransactionStatistics([
    TransactionKind::VIBAN_PURCHASE => new VIbanPurchaseTransactionManager(),
]);

$actual = $stats->forGroupedByKind($grouped);

dump($actual);
