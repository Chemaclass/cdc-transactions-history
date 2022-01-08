<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\Transfer\Transaction;

final class ToCurrencyAggregator implements TransactionAggregatorInterface
{
    private int $totalDecimals;

    private int $totalNativeDecimals;

    public function __construct(int $totalDecimals, int $totalNativeDecimals)
    {
        $this->totalDecimals = $totalDecimals;
        $this->totalNativeDecimals = $totalNativeDecimals;
    }

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

            $totalAmount = (float) $result[$currency]['total'] + $transaction->getToAmount();
            $result[$currency]['total'] = number_format($totalAmount, $this->totalDecimals);

            $totalAmount = (float) $result[$currency]['totalInEuros'] + $transaction->getNativeAmount();
            $result[$currency]['totalInEuros'] = number_format($totalAmount, $this->totalNativeDecimals);
        }

        return $result;
    }
}
