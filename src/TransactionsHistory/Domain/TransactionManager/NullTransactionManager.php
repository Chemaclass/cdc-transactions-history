<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionManager;

use App\TransactionsHistory\Domain\Transfer\Transaction;

final class NullTransactionManager implements TransactionManagerInterface
{
    public function manageTransactions(Transaction ...$transactions): array
    {
        return [];
    }
}
