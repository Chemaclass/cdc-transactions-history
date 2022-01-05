<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\TransactionManager;

use App\Domain\Transaction;
use App\Domain\TransactionKind;
use App\Domain\TransactionManager\VIbanPurchaseTransactionManager;
use PHPUnit\Framework\TestCase;

final class VIbanPurchaseTransactionManagerTest extends TestCase
{
    public function test_total(): void
    {
        $transactions = [
            Transaction::fromArray([
                'Currency' => 'EUR',
                'Amount' => '-100',
                'To Currency' => 'BCH',
                'To Amount' => '1',
                'Native Currency' => 'EUR',
                'Native Amount' => '100',
                'Transaction Kind' => TransactionKind::VIBAN_PURCHASE,
            ]),
            Transaction::fromArray([
                'Currency' => 'EUR',
                'Amount' => '-200',
                'To Currency' => 'DOT',
                'To Amount' => '2',
                'Native Currency' => 'EUR',
                'Native Amount' => '200',
                'Transaction Kind' => TransactionKind::VIBAN_PURCHASE,
            ]),
            Transaction::fromArray([
                'Currency' => 'EUR',
                'Amount' => '-300',
                'To Currency' => 'ADA',
                'To Amount' => '3',
                'Native Currency' => 'EUR',
                'Native Amount' => '300',
                'Transaction Kind' => TransactionKind::VIBAN_PURCHASE,
            ]),
        ];

        $manager = new VIbanPurchaseTransactionManager();

        self::assertEquals([
            'BCH' => [
                'totalInEuros' => 100,
            ],
            'DOT' => [
                'totalInEuros' => 200,
            ],
            'ADA' => [
                'totalInEuros' => 300,
            ],
        ], $manager->manageTransactions(...$transactions));
    }
}
