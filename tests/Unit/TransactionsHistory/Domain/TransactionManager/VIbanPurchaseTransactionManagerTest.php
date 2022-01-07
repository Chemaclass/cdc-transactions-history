<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\TransactionManager;

use App\TransactionsHistory\Domain\TransactionManager\VIbanPurchaseTransactionManager;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use PHPUnit\Framework\TestCase;

final class VIbanPurchaseTransactionManagerTest extends TestCase
{
    public function test_manage_transactions(): void
    {
        $transactions = [
            (new Transaction())->setToCurrency('BCH')->setNativeAmount(10),
            (new Transaction())->setToCurrency('DOT')->setNativeAmount(20.2),
            (new Transaction())->setToCurrency('BCH')->setNativeAmount(30.33),
        ];

        $manager = new VIbanPurchaseTransactionManager();

        self::assertSame([
            'BCH' => [
                'totalInEuros' => 40.33,
            ],
            'DOT' => [
                'totalInEuros' => 20.2,
            ],
        ], $manager->manageTransactions(...$transactions));
    }
}
