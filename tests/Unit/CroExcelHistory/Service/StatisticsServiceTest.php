<?php

declare(strict_types=1);

namespace Tests\Unit\CroExcelHistory\Service;

use App\CroExcelHistory\Mapper\TransactionMapper;
use App\CroExcelHistory\Service\StatisticsService;
use App\CroExcelHistory\TransactionManager\TransactionManagerInterface;
use App\CroExcelHistory\Transfer\TransactionKind;
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
            new TransactionMapper(),
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
