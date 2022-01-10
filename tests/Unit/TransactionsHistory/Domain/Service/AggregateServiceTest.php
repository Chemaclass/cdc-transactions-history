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

final class AggregateServiceTest extends TestCase
{
    public function test_service_with_multiple_transaction_types(): void
    {
        $transactions = [
            [
                'Transaction Kind Header' => 'transaction type 1',
            ],
            [
                'Transaction Kind Header' => 'transaction type 1',
            ],
            [
                'Transaction Kind Header' => 'transaction type 2',
            ],
        ];

        $fileReaderService = $this->createMock(FileReaderServiceInterface::class);
        $fileReaderService->method('read')->with('fake-file-path')->willReturn($transactions);

        $aggregator = new class() implements TransactionAggregatorInterface {
            public function aggregate(Transaction ...$transactions): array
            {
                return ['type' => 'manager'];
            }
        };

        $transactionMapper = $this->createMock(TransactionMapperInterface::class);
        $transactionMapper->method('map')->willReturnCallback(
            fn(array $row) => (new Transaction())
                ->setTransactionType($row['Transaction Kind Header'])
                ->setAggregatorClassName(get_class($aggregator))
        );

        $statisticsService = new AggregateService(
            $fileReaderService,
            $transactionMapper,
            (new TransactionAggregators())->add($aggregator),
        );

        self::assertEquals([
            'transaction type 1' => ['type' => 'manager'],
            'transaction type 2' => ['type' => 'manager'],
        ], $statisticsService->forFilepath('fake-file-path'));
    }
}
