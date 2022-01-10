<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\Service;

use App\TransactionsHistory\Domain\IO\FileReaderServiceInterface;
use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Service\AggregateService;
use App\TransactionsHistory\Domain\TransactionAggregator\TransactionAggregatorInterface;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use App\TransactionsHistory\Domain\Transfer\TransactionAggregators;
use PHPUnit\Framework\TestCase;

final class StatisticsServiceTest extends TestCase
{
    public function test_service_with_multiple_transaction_kinds(): void
    {
        $transactions = [
            [
                'Transaction Kind Header' => 'transaction kind 1',
            ],
            [
                'Transaction Kind Header' => 'transaction kind 1',
            ],
            [
                'Transaction Kind Header' => 'transaction kind 2',
            ],
        ];

        $fileReaderService = $this->createMock(FileReaderServiceInterface::class);
        $fileReaderService->method('read')->with('fake-file-path')->willReturn($transactions);

        $transactionMapper = $this->createMock(TransactionMapperInterface::class);
        $transactionMapper->method('map')->willReturnCallback(
            fn(array $row) => (new Transaction())->setTransactionType($row['Transaction Kind Header'])
        );

        $kind1Aggregator = $this->createMock(TransactionAggregatorInterface::class);
        $kind1Aggregator->method('aggregate')->willReturn(['kind 1' => 'manager']);

        $kind2Aggregator = $this->createMock(TransactionAggregatorInterface::class);
        $kind2Aggregator->method('aggregate')->willReturn(['kind 2' => 'manager']);

        $transactionManagers = (new TransactionAggregators())
            ->put('transaction kind 1', $kind1Aggregator)
            ->put('transaction kind 2', $kind2Aggregator);

        $statisticsService = new AggregateService(
            $fileReaderService,
            $transactionMapper,
            $transactionManagers,
        );

        self::assertEquals([
            'transaction kind 1' => ['kind 1' => 'manager'],
            'transaction kind 2' => ['kind 2' => 'manager'],
        ], $statisticsService->forFilepath('fake-file-path'));
    }
}
