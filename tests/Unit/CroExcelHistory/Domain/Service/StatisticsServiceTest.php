<?php

declare(strict_types=1);

namespace Tests\Unit\CroExcelHistory\Domain\Service;

use App\CroExcelHistory\Domain\Mapper\CsvHeadersTransactionMapper;
use App\CroExcelHistory\Domain\Service\StatisticsService;
use App\CroExcelHistory\Domain\TransactionManager\TransactionManagerInterface;
use App\CroExcelHistory\Domain\Transfer\TransactionKind;
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

        $purchaseManager = $this->createMock(TransactionManagerInterface::class);
        $purchaseManager->method('manageTransactions')->willReturn(['purchase' => 'manager']);

        $withdrawalManager = $this->createMock(TransactionManagerInterface::class);
        $withdrawalManager->method('manageTransactions')->willReturn(['withdrawal' => 'manager']);

        $stats = new StatisticsService(
            new CsvHeadersTransactionMapper(),
            [
                TransactionKind::VIBAN_PURCHASE => $purchaseManager,
                TransactionKind::CRYPTO_WITHDRAWAL => $withdrawalManager,
            ]
        );

        self::assertEquals([
            TransactionKind::VIBAN_PURCHASE => ['purchase' => 'manager'],
            TransactionKind::CRYPTO_WITHDRAWAL => ['withdrawal' => 'manager'],
        ], $stats->forCsv($transactions));
    }
}
