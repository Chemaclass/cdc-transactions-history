<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\Service;

use App\TransactionsHistory\Domain\IO\FileReaderServiceInterface;
use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Service\StatisticsService;
use App\TransactionsHistory\Domain\TransactionManager\TransactionManagerInterface;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use App\TransactionsHistory\Domain\Transfer\TransactionManagers;
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
            fn(array $row) => (new Transaction())->setTransactionKind($row['Transaction Kind Header'])
        );

        $kind1Manager = $this->createMock(TransactionManagerInterface::class);
        $kind1Manager->method('manageTransactions')->willReturn(['kind 1' => 'manager']);

        $kind2Manager = $this->createMock(TransactionManagerInterface::class);
        $kind2Manager->method('manageTransactions')->willReturn(['kind 2' => 'manager']);

        $transactionManagers = (new TransactionManagers())
            ->add('transaction kind 1', $kind1Manager)
            ->add('transaction kind 2', $kind2Manager);

        $statisticsService = new StatisticsService(
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
