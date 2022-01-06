<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Domain\TransactionManager;

use App\CroExcelHistory\Domain\Transfer\Transaction;

final class NullTransactionManager implements TransactionManagerInterface
{
    public function manageTransactions(Transaction ...$transactions): array
    {
        return [];
    }
}
