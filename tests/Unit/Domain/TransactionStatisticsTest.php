<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\GroupedTransactions;
use App\Domain\Transaction;
use App\Domain\TransactionKind;
use App\Domain\TransactionManager\TransactionManagerInterface;
use App\Domain\TransactionStatistics;
use PHPUnit\Framework\TestCase;

final class TransactionStatisticsTest extends TestCase
{
    public function test_for_grouped_by_kind(): void
    {
        $transactions = [
            Transaction::fromArray([
                'Currency' => 'EUR',
                'Amount' => '-480.13',
                'To Currency' => 'BCH',
                'To Amount' => '0.4375',
                'Native Currency' => 'EUR',
                'Native Amount' => '480.13',
                'Native Amount (in USD)' => '544.3286432248',
                'Transaction Kind' => TransactionKind::VIBAN_PURCHASE,
            ]),
            Transaction::fromArray([
                'Currency' => 'EUR',
                'Amount' => '-148.98',
                'To Currency' => 'BCH',
                'To Amount' => '0.1265',
                'Native Currency' => 'EUR',
                'Native Amount' => '148.98',
                'Native Amount (in USD)' => '168.9002588208',
                'Transaction Kind' => TransactionKind::VIBAN_PURCHASE,
            ]),
            Transaction::fromArray([
                'Currency' => 'EUR',
                'Amount' => '-34.38',
                'To Currency' => 'DOT',
                'To Amount' => '1000.0',
                'Native Currency' => 'EUR',
                'Native Amount' => '34.38',
                'Native Amount (in USD)' => '38.9769828048',
                'Transaction Kind' => TransactionKind::VIBAN_PURCHASE,
            ]),
            Transaction::fromArray([
                'Currency' => 'EUR',
                'Amount' => '-110.06',
                'To Currency' => 'ADA',
                'To Amount' => '100',
                'Native Currency' => 'EUR',
                'Native Amount' => '110.06',
                'Native Amount (in USD)' => '124.7762282576',
                'Transaction Kind' => TransactionKind::CRYPTO_WITHDRAWAL,
            ]),
        ];

        $grouped = (new GroupedTransactions())->byKind(...$transactions);

        $purchaseManager = $this->createMock(TransactionManagerInterface::class);
        $purchaseManager->method('manageTransactions')->willReturn(['purchase' => 'manager']);

        $withdrawalManager = $this->createMock(TransactionManagerInterface::class);
        $withdrawalManager->method('manageTransactions')->willReturn(['withdrawal' => 'manager']);

        $stats = new TransactionStatistics([
            TransactionKind::VIBAN_PURCHASE => $purchaseManager,
            TransactionKind::CRYPTO_WITHDRAWAL => $withdrawalManager,
        ]);

        self::assertEquals([
            TransactionKind::VIBAN_PURCHASE => ['purchase' => 'manager'],
            TransactionKind::CRYPTO_WITHDRAWAL => ['withdrawal' => 'manager'],
        ], $stats->forGroupedByKind($grouped));
    }
}
