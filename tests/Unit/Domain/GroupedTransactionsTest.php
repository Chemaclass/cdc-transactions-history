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
            (new Transaction())->setTransactionKind('transaction kind 1'),
            (new Transaction())->setTransactionKind('transaction kind 1'),
            (new Transaction())->setTransactionKind('transaction kind 2'),
        ];

        $groupedTransactions = new GroupedTransactions();

        self::assertEquals([
            'transaction kind 1' => [
                (new Transaction())->setTransactionKind('transaction kind 1'),
                (new Transaction())->setTransactionKind('transaction kind 1'),
            ],
            'transaction kind 2' => [
                (new Transaction())->setTransactionKind('transaction kind 2'),
            ],
        ], $groupedTransactions->byKind(...$transactions));
    }
}
