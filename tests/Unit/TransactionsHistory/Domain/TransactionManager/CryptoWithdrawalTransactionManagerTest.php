<?php

declare(strict_types=1);

namespace Tests\Unit\TransactionsHistory\Domain\TransactionManager;

use App\TransactionsHistory\Domain\TransactionManager\CryptoWithdrawalTransactionManager;
use App\TransactionsHistory\Domain\Transfer\Transaction;
use PHPUnit\Framework\TestCase;

final class CryptoWithdrawalTransactionManagerTest extends TestCase
{
    public function test_manage_transactions(): void
    {
        $transactions = [
            (new Transaction())->setCurrency('BCH')->setAmount(1)->setNativeAmount(10),
            (new Transaction())->setCurrency('DOT')->setAmount(2)->setNativeAmount(20.2),
            (new Transaction())->setCurrency('ADA')->setAmount(3)->setNativeAmount(30.33),
        ];

        $manager = new CryptoWithdrawalTransactionManager();

        self::assertSame([
            'BCH' => [
                'total' => 1.0,
                'totalInEuros' => 10.0,
            ],
            'DOT' => [
                'total' => 2.0,
                'totalInEuros' => 20.2,
            ],
            'ADA' => [
                'total' => 3.0,
                'totalInEuros' => 30.33,
            ],
        ], $manager->manageTransactions(...$transactions));
    }
}
