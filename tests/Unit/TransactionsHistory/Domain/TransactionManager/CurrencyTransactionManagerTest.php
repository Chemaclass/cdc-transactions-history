<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\TransactionManager;

use App\TransactionsHistory\Domain\TransactionManager\CurrencyTransactionManager;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use PHPUnit\Framework\TestCase;

final class CurrencyTransactionManagerTest extends TestCase
{
    public function test_manage_transactions(): void
    {
        $transactions = [
            (new Transaction())->setCurrency('BCH')->setAmount(1)->setNativeAmount(10),
            (new Transaction())->setCurrency('DOT')->setAmount(2)->setNativeAmount(20.2),
            (new Transaction())->setCurrency('BCH')->setAmount(3)->setNativeAmount(30.33),
        ];

        $manager = new CurrencyTransactionManager();

        self::assertSame([
            'BCH' => [
                'total' => 4.0,
                'totalInEuros' => 40.33,
            ],
            'DOT' => [
                'total' => 2.0,
                'totalInEuros' => 20.2,
            ],
        ], $manager->manageTransactions(...$transactions));
    }
}
