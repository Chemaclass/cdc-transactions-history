<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionManager;

use App\TransactionsHistory\Domain\Transfer\Transaction;

final class CurrencyTransactionManager implements TransactionManagerInterface
{
    /**
     * @return array<string,array<string,mixed>>
     */
    public function manageTransactions(Transaction ...$transactions): array
    {
        $result = [];

        foreach ($transactions as $transaction) {
            $currency = $transaction->getCurrency();

            $result[$currency] ??= [
                'total' => 0,
                'totalInEuros' => 0,
            ];

            $result[$currency]['total'] += $transaction->getAmount();
            $result[$currency]['totalInEuros'] += $transaction->getNativeAmount();
        }

        return $result;
    }
}
