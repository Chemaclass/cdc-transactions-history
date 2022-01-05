<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\GroupedTransactions;
use App\Domain\Transaction;
use PHPUnit\Framework\TestCase;

final class GroupedTransactionsTest extends TestCase
{
    public function test_group_by_kind(): void
    {
        $transactions = [
            Transaction::fromArray([
                'Transaction Kind' => 'transaction kind 1',
            ]),
            Transaction::fromArray([
                'Transaction Kind' => 'transaction kind 1',
            ]),
            Transaction::fromArray([
                'Transaction Kind' => 'transaction kind 2',
            ]),
        ];

        $groupedTransactions = new GroupedTransactions();
        $actual = $groupedTransactions->byKind(...$transactions);

        $expected = [
            'transaction kind 1' => [
                Transaction::fromArray([
                    'Transaction Kind' => 'transaction kind 1',
                ]),
                Transaction::fromArray([
                    'Transaction Kind' => 'transaction kind 1',
                ]),
            ],
            'transaction kind 2' => [
                Transaction::fromArray([
                    'Transaction Kind' => 'transaction kind 2',
                ]),
            ],
        ];

        self::assertEquals($expected, $actual);
    }
}