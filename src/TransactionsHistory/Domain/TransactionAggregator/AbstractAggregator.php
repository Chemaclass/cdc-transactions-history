<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\Transfer\Transaction;
use Safe\DateTimeImmutable;

abstract class AbstractAggregator implements TransactionAggregatorInterface
{
    private const TOTAL_DECIMALS_IN_USD = 2;

    private int $totalDecimals;

    private int $totalNativeDecimals;

    private ?string $nativeCurrencyKey;

    public function __construct(int $totalDecimals, int $totalNativeDecimals, ?string $nativeCurrencyKey = null)
    {
        $this->totalDecimals = $totalDecimals;
        $this->totalNativeDecimals = $totalNativeDecimals;
        $this->nativeCurrencyKey = $nativeCurrencyKey;
    }

    /**
     * @return array<string,array<string,string>>
     */
    public function aggregate(Transaction ...$transactions): array
    {
        $result = [];

        foreach ($transactions as $transaction) {
            $currency = $this->getCurrency($transaction);
            $nativeCurrency = $this->nativeCurrencyKey ?? $transaction->getNativeCurrency();

            $result[$currency] ??= [
                'description' => '',
                'total' => '0.0',
                $nativeCurrency => '0.0',
                'USD' => '0.0',
                'last_datetime' => '',
            ];

            $result[$currency]['description'] = $transaction->getTransactionDescription();
            $result[$currency]['total'] = $this->calculateTotalAmount($transaction, $result);
            $result[$currency][$nativeCurrency] = $this->calculateNativeAmount($transaction, $result);
            $result[$currency]['USD'] = $this->calculateUSDAmount($transaction, $result);
            $result[$currency]['last_datetime'] = $this->calculateLastDateTime($transaction, $result, $currency);
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

        $amount = number_format($totalAmount, $this->totalDecimals);

        return rtrim(rtrim($amount, '0'), '.');
    }

    /**
     * @param array<string,array<string,string>> $result
     */
    private function calculateNativeAmount(Transaction $transaction, array $result): string
    {
        $nativeCurrency = $this->nativeCurrencyKey ?? $transaction->getNativeCurrency();
        $currentAmount = (float) $result[$this->getCurrency($transaction)][$nativeCurrency];
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

    /**
     * @param array<string,array<string,string>> $result
     */
    private function calculateLastDateTime(Transaction $transaction, array $result, string $currency): string
    {
        if (empty($result[$currency]['last_datetime'])) {
            return $transaction->getTimestampUtc();
        }
        $currentLastDateTime = new DateTimeImmutable($result[$currency]['last_datetime']);
        $newLastLastDateTime = new DateTimeImmutable($transaction->getTimestampUtc());

        return ($currentLastDateTime < $newLastLastDateTime)
            ? $transaction->getTimestampUtc()
            : $result[$currency]['last_datetime'];
    }
}
