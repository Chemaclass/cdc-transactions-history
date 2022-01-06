<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionManager;

use App\TransactionsHistory\Domain\Transfer\Transaction;

final class CryptoWithdrawalTransactionManager implements TransactionManagerInterface
{
    /**
     * @return array<string,array<string,mixed>>
     */
    public function manageTransactions(Transaction ...$transactions): array
    {
        $result = [];

        foreach ($transactions as $transaction) {
            $result[$transaction->getCurrency()] ??= [
                'total' => 0,
                'totalInEuros' => 0,
            ];

            $result[$transaction->getCurrency()]['total'] += $transaction->getAmount();
            $result[$transaction->getCurrency()]['totalInEuros'] += $transaction->getNativeAmount();
        }

        return $result;
    }
}
