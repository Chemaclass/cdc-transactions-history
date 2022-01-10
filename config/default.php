<?php

declare(strict_types=1);

use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use App\TransactionsHistory\TransactionsHistoryConfig;

return [
    TransactionsHistoryConfig::TOTAL_DECIMALS => 8,
    TransactionsHistoryConfig::TOTAL_NATIVE_DECIMALS => 2,
    TransactionsHistoryConfig::NATIVE_CURRENCY_KEY => 'EUR',
    TransactionsHistoryConfig::TRANSACTION_AGGREGATOR_CLASS_NAME_BY_KIND => [
        'crypto_deposit' => CurrencyAggregator::class,
        'crypto_earn_interest_paid' => CurrencyAggregator::class,
        'crypto_exchange' => ToCurrencyAggregator::class,
        'crypto_purchase' => CurrencyAggregator::class,
        'crypto_to_exchange_transfer' => CurrencyAggregator::class,
        'crypto_viban_exchange' => ToCurrencyAggregator::class,
        'crypto_withdrawal' => CurrencyAggregator::class,
        'viban_purchase' => ToCurrencyAggregator::class,
    ],
];
