<?php

declare(strict_types=1);

namespace App\CroExcelHistory\TransactionManager;

use App\CroExcelHistory\Transfer\Transaction;

final class VIbanPurchaseTransactionManager implements TransactionManagerInterface
{
    /**
     * @return array<string,array<string,mixed>>
     */
    public function manageTransactions(Transaction ...$transactions): array
    {
        $result = [];

        foreach ($transactions as $transaction) {
            $result[$transaction->getToCurrency()] ??= [
                'totalInEuros' => 0,
            ];

            $result[$transaction->getToCurrency()]['totalInEuros'] += $transaction->getNativeAmount();
        }

        return $result;
    }
}
