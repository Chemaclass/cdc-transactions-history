<?php

declare(strict_types=1);

namespace App\CroExcelHistory\TransactionManager;

use App\CroExcelHistory\Transfer\Transaction;

interface TransactionManagerInterface
{
    /**
     * @return array<string,mixed>
     */
    public function manageTransactions(Transaction ...$transactions): array;
}
