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

        $aggregator1 = new class() implements TransactionAggregatorInterface {
            public function aggregate(Transaction ...$transactions): array
            {
                return ['type1' => 'manager1'];
            }
        };

        $aggregator2 = new class() implements TransactionAggregatorInterface {
            public function aggregate(Transaction ...$transactions): array
            {
                return ['type2' => 'manager2'];
            }
        };

        $transactionMapper = $this->createMock(TransactionMapperInterface::class);
        $transactionMapper->method('map')->willReturnCallback(
            fn(array $row) => (new Transaction())
                ->setTransactionType($row['Transaction Kind Header'])
        );

        $statisticsService = new AggregateService(
            $fileReaderService,
            $transactionMapper,
            (new TransactionAggregators())
                ->add($aggregator1)
                ->add($aggregator2),
        );

        self::assertEquals([
            'transaction type 1' => ['type1' => 'manager1', 'type2' => 'manager2'],
            'transaction type 2' => ['type1' => 'manager1', 'type2' => 'manager2'],
        ], $statisticsService->forFilepath('fake-file-path'));
    }
}
