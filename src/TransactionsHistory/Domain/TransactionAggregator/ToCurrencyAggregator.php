<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\Transfer\Transaction;

final class ToCurrencyAggregator implements TransactionAggregatorInterface
{
    /**
     * @return array<string,array<string,mixed>>
     */
    public function aggregate(Transaction ...$transactions): array
    {
        $result = [];

        foreach ($transactions as $transaction) {
            $currency = $transaction->getToCurrency();

            $result[$currency] ??= [
                'total' => 0,
                'totalInEuros' => 0,
            ];

            $result[$currency]['total'] += $transaction->getToAmount();
            $result[$currency]['totalInEuros'] += $transaction->getNativeAmount();
        }

        return $result;
    }
}
