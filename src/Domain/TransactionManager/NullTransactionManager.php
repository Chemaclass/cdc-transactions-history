<?php

declare(strict_types=1);

namespace App\Domain\TransactionManager;

use App\Domain\Transaction;

final class NullTransactionManager implements TransactionManagerInterface
{
    public function manageTransactions(Transaction ...$transactions): array
    {
        return [];
    }
}
