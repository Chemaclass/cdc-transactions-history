<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Service;

use App\CroExcelHistory\Transfer\Transaction;

final class GroupedTransactions
{
    /**
     * @return array<string,list<Transaction>>
     */
    public function byKind(Transaction ...$transactions): array
    {
        $result = [];

        foreach ($transactions as $transaction) {
            $result[$transaction->getTransactionKind()] ??= [];
            $result[$transaction->getTransactionKind()][] = $transaction;
        }

        return $result;
    }
}
