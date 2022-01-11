<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Infrastructure\Mapper;

use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use App\TransactionsHistory\Domain\Transfer\Transaction;

final class CsvHeadersTransactionMapper implements TransactionMapperInterface
{
    /**
     * @param array<string,string> $array
     */
    public function map(array $array): Transaction
    {
        $transaction = (new Transaction())
            ->setTimestampUtc($array['Timestamp (UTC)'] ?? '')
            ->setTransactionDescription($array['Transaction Description'] ?? '')
            ->setCurrency($array['Currency'] ?? '')
            ->setAmount((float) ($array['Amount'] ?? ''))
            ->setToCurrency($array['To Currency'] ?? '')
            ->setToAmount((float) ($array['To Amount'] ?? ''))
            ->setNativeCurrency($array['Native Currency'] ?? '')
            ->setNativeAmount((float) ($array['Native Amount'] ?? ''))
            ->setNativeAmountInUSD((float) ($array['Native Amount (in USD)'] ?? ''))
            ->setTransactionType($array['Transaction Kind'] ?? '');

        $transaction->setAggregatorClassName($this->getAggregatorClassName($transaction));

        return $transaction;
    }

    /**
     * @return class-string
     */
    private function getAggregatorClassName(Transaction $transaction): string
    {
        if (!empty($transaction->getToAmount())) {
            return ToCurrencyAggregator::class;
        }

        return CurrencyAggregator::class;
    }
}
