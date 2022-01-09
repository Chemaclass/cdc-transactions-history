<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\Transfer\Transaction;

final class ToCurrencyAggregator extends AbstractAggregator
{
    protected function getCurrency(Transaction $transaction): string
    {
        return $transaction->getToCurrency();
    }

    protected function getAmount(Transaction $transaction): float
    {
        return $transaction->getToAmount();
    }
}
