<?php

declare(strict_types=1);

namespace App\Domain;

final class GroupedTransactions
{
    /**
     * @return array<string,list<Transaction>>
     */
    public function byKind(Transaction ...$transactions): array
    {
        $result = [];

        foreach ($transactions as $transaction) {
            $result[$transaction->transactionKind()] ??= [];
            $result[$transaction->transactionKind()][] = $transaction;
        }

        return $result;
    }
}
