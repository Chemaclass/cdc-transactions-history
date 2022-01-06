<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Domain\TransactionManager;

use App\CroExcelHistory\Domain\Transfer\Transaction;

interface TransactionManagerInterface
{
    /**
     * @return array<string,mixed>
     */
    public function manageTransactions(Transaction ...$transactions): array;
}
