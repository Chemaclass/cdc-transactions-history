<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Service;

use App\CroExcelHistory\Transfer\Transaction;

final class TransactionMapper
{
    /**
     * @param array<string,string> $row
     */
    public function map(array $row): Transaction
    {
        return Transaction::fromArray($row);
    }
}
