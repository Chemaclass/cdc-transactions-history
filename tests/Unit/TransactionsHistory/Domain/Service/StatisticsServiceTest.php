<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\Service;

use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Service\StatisticsService;
use App\TransactionsHistory\Domain\TransactionManager\TransactionManagerInterface;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use App\TransactionsHistory\Domain\Transfer\TransactionKind;
use App\TransactionsHistory\Infrastructure\IO\FileReaderServiceInterface;
use PHPUnit\Framework\TestCase;

final class StatisticsServiceTest extends TestCase
{
    public function test_for_csv(): void
    {
        $transactions = [
            [
                'Transaction Kind' => TransactionKind::VIBAN_PURCHASE,
            ],
            [
                'Transaction Kind' => TransactionKind::VIBAN_PURCHASE,
            ],
            [
                'Transaction Kind' => TransactionKind::CRYPTO_WITHDRAWAL,
            ],
        ];

        $fileReaderService = $this->createMock(FileReaderServiceInterface::class);
        $fileReaderService->method('read')->willReturn($transactions);

        $transactionMapper = $this->createMock(TransactionMapperInterface::class);
        $transactionMapper->method('map')->willReturnCallback(
            fn(array $row) => (new Transaction())->setTransactionKind($row['Transaction Kind'])
        );

        $purchaseManager = $this->createMock(TransactionManagerInterface::class);
        $purchaseManager->method('manageTransactions')->willReturn(['purchase' => 'manager']);

        $withdrawalManager = $this->createMock(TransactionManagerInterface::class);
        $withdrawalManager->method('manageTransactions')->willReturn(['withdrawal' => 'manager']);

        $stats = new StatisticsService(
            $fileReaderService,
            $transactionMapper,
            [
                TransactionKind::VIBAN_PURCHASE => $purchaseManager,
                TransactionKind::CRYPTO_WITHDRAWAL => $withdrawalManager,
            ]
        );

        self::assertEquals([
            TransactionKind::VIBAN_PURCHASE => ['purchase' => 'manager'],
            TransactionKind::CRYPTO_WITHDRAWAL => ['withdrawal' => 'manager'],
        ], $stats->forFilepath('file-path'));
    }
}
