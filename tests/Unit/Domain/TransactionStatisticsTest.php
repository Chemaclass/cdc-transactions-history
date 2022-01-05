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
        $bch1 = (new Transaction())
            ->setCurrency('EUR')
            ->setAmount(-100)
            ->setToCurrency('BCH')
            ->setToAmount(0.5)
            ->setNativeCurrency('EUR')
            ->setNativeAmount(100)
            ->setTransactionKind(TransactionKind::VIBAN_PURCHASE);

        $bch2 = (new Transaction())
            ->setCurrency('EUR')
            ->setAmount(-200)
            ->setToCurrency('BCH')
            ->setToAmount(1.2)
            ->setNativeCurrency('EUR')
            ->setNativeAmount(200)
            ->setTransactionKind(TransactionKind::VIBAN_PURCHASE);

        $dot1 = (new Transaction())
            ->setCurrency('EUR')
            ->setAmount(-300)
            ->setToCurrency('DOT')
            ->setToAmount(30)
            ->setNativeCurrency('EUR')
            ->setNativeAmount(300)
            ->setTransactionKind(TransactionKind::CRYPTO_WITHDRAWAL);

        $transactions = [
            $bch1,
            $bch2,
            $dot1,
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
