<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\Transfer\Transaction;

abstract class AbstractAggregator implements TransactionAggregatorInterface
{
    private const TOTAL_DECIMALS_IN_USD = 2;

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
            $currency = $this->getCurrency($transaction);

            $result[$currency] ??= [
                'total' => 0.0,
                'totalInNative' => 0.0,
                'totalInUSD' => 0.0,
            ];

            $totalAmount = ((float) $result[$currency]['total']) + $this->getAmount($transaction);
            $result[$currency]['total'] = number_format($totalAmount, $this->totalDecimals);

            $totalAmount = (float) $result[$currency]['totalInNative'] + $transaction->getNativeAmount();
            $result[$currency]['totalInNative'] = number_format($totalAmount, $this->totalNativeDecimals);

            $totalAmount = (float) $result[$currency]['totalInUSD'] + $transaction->getNativeAmountInUSD();
            $result[$currency]['totalInUSD'] = number_format($totalAmount, self::TOTAL_DECIMALS_IN_USD);
        }

        return $result;
    }

    abstract protected function getCurrency(Transaction $transaction): string;

    abstract protected function getAmount(Transaction $transaction): float;
}
