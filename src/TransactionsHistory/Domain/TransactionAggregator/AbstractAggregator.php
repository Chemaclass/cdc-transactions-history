<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\Transfer\Transaction;

abstract class AbstractAggregator implements TransactionAggregatorInterface
{
    private const TOTAL_DECIMALS_IN_USD = 2;

    private int $totalDecimals;

    private string $nativeCurrencyKey;

    private int $totalNativeDecimals;

    public function __construct(int $totalDecimals, string $nativeCurrencyKey, int $totalNativeDecimals)
    {
        $this->totalDecimals = $totalDecimals;
        $this->nativeCurrencyKey = $nativeCurrencyKey;
        $this->totalNativeDecimals = $totalNativeDecimals;
    }

    /**
     * @return array<string,array<string,string>>
     */
    public function aggregate(Transaction ...$transactions): array
    {
        $result = [];

        foreach ($transactions as $transaction) {
            $currency = $this->getCurrency($transaction);

            $result[$currency] ??= [
                'total' => '0.0',
                $this->nativeCurrencyKey => '0.0',
                'USD' => '0.0',
                'description' => '',
            ];

            $result[$currency]['total'] = $this->calculateTotalAmount($transaction, $result);
            $result[$currency][$this->nativeCurrencyKey] = $this->calculateNativeAmount($transaction, $result);
            $result[$currency]['USD'] = $this->calculateUSDAmount($transaction, $result);
            $result[$currency]['description'] = $transaction->getTransactionDescription();
        }

        return $result;
    }

    abstract protected function getCurrency(Transaction $transaction): string;

    abstract protected function getAmountForTotal(Transaction $transaction): float;

    /**
     * @param array<string,array<string,string>> $result
     */
    private function calculateTotalAmount(Transaction $transaction, array $result): string
    {
        $currentAmount = (float) $result[$this->getCurrency($transaction)]['total'];
        $totalAmount = $currentAmount + $this->getAmountForTotal($transaction);

        return number_format($totalAmount, $this->totalDecimals);
    }

    /**
     * @param array<string,array<string,string>> $result
     */
    private function calculateNativeAmount(Transaction $transaction, array $result): string
    {
        $currentAmount = (float) $result[$this->getCurrency($transaction)][$this->nativeCurrencyKey];
        $totalAmount = $currentAmount + $transaction->getNativeAmount();

        return number_format($totalAmount, $this->totalNativeDecimals);
    }

    /**
     * @param array<string,array<string,string>> $result
     */
    private function calculateUSDAmount(Transaction $transaction, array $result): string
    {
        $currentAmount = (float) $result[$this->getCurrency($transaction)]['USD'];
        $totalAmount = $currentAmount + $transaction->getNativeAmountInUSD();

        return number_format($totalAmount, self::TOTAL_DECIMALS_IN_USD);
    }
}
