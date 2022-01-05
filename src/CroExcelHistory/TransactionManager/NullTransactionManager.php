<?php

declare(strict_types=1);

namespace App\CroExcelHistory\TransactionManager;

use App\CroExcelHistory\Transfer\Transaction;

final class NullTransactionManager implements TransactionManagerInterface
{
    public function manageTransactions(Transaction ...$transactions): array
    {
        return [];
    }
}
