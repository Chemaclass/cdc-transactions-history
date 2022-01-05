<?php

declare(strict_types=1);

namespace App\Domain\TransactionManager;

use App\Domain\Transaction;

final class VIbanPurchaseTransactionManager implements TransactionManagerInterface
{
    /**
     * @return array<string,array<string,mixed>>
     */
    public function manageTransactions(Transaction ...$transactions): array
    {
        $result = [];

        foreach ($transactions as $transaction) {
            $result[$transaction->toCurrency()] ??= [
                'totalInEuros' => 0,
            ];

            $result[$transaction->toCurrency()]['totalInEuros'] += $transaction->nativeAmount();
        }

        return $result;
    }
}
