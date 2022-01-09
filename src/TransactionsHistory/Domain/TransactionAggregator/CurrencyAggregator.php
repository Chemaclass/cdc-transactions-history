<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\TransactionAggregator;

use App\TransactionsHistory\Domain\Transfer\Transaction;

final class CurrencyAggregator extends AbstractAggregator
{
    protected function getCurrency(Transaction $transaction): string
    {
        return $transaction->getCurrency();
    }

    protected function getAmount(Transaction $transaction): float
    {
        return $transaction->getAmount();
    }
}
