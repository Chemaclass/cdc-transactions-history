<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionManager;

use App\TransactionsHistory\Domain\Transfer\Transaction;

interface TransactionManagerInterface
{
    /**
     * @return array<string,mixed>
     */
    public function manageTransactions(Transaction ...$transactions): array;
}
