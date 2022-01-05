<?php

declare(strict_types=1);

namespace App\Domain;

final class GroupedTransactions
{
    /**
     * By currency and kind
     */
    public function byKind(Transaction ...$transactions): array
    {
        $result = [];
        foreach ($transactions as $transaction) {
            $result[$transaction->transactionKind] ??= [];
            $result[$transaction->transactionKind][] = $transaction;
        }

        return $result;
    }

    public function uniqueKinds(Transaction ...$transactions): array
    {
        $transactionKinds = array_map(
            static fn(Transaction $t) => $t->transactionKind,
            $transactions
        );

        return array_values(array_unique($transactionKinds));
    }
}