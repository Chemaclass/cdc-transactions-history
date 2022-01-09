<?php

declare(strict_types=1);

use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use App\TransactionsHistory\TransactionsHistoryConfig;

return [
    TransactionsHistoryConfig::TOTAL_DECIMALS => 8,
    TransactionsHistoryConfig::TOTAL_NATIVE_DECIMALS => 2,
    TransactionsHistoryConfig::NATIVE_CURRENCY_KEY => 'EUR',
    TransactionsHistoryConfig::TRANSACTION_KIND_AGGREGATORS => [
        'card_cashback_reverted' => CurrencyAggregator::class,
        'crypto_deposit' => CurrencyAggregator::class,
        'crypto_earn_interest_paid' => CurrencyAggregator::class,
        'crypto_earn_program_created' => CurrencyAggregator::class,
        'crypto_earn_program_withdrawn' => CurrencyAggregator::class,
        'crypto_exchange' => ToCurrencyAggregator::class,
        'crypto_payment' => ToCurrencyAggregator::class,
        'crypto_purchase' => CurrencyAggregator::class,
        'crypto_to_exchange_transfer' => CurrencyAggregator::class,
        'crypto_viban_exchange' => ToCurrencyAggregator::class,
        'crypto_withdrawal' => CurrencyAggregator::class,
        'dust_conversion_credited' => CurrencyAggregator::class,
        'dust_conversion_debited' => CurrencyAggregator::class,
        'exchange_to_crypto_transfer' => CurrencyAggregator::class,
        'lockup_lock' => CurrencyAggregator::class,
        'lockup_upgrade' => CurrencyAggregator::class,
        'mco_stake_reward' => CurrencyAggregator::class,
        'mobile_airtime_reward' => CurrencyAggregator::class,
        'referral_bonus' => CurrencyAggregator::class,
        'referral_card_cashback' => CurrencyAggregator::class,
        'reimbursement' => CurrencyAggregator::class,
        'rewards_platform_deposit_credited' => CurrencyAggregator::class,
        'supercharger_deposit' => CurrencyAggregator::class,
        'supercharger_reward_to_app_credited' => CurrencyAggregator::class,
        'supercharger_withdrawal' => CurrencyAggregator::class,
        'viban_purchase' => ToCurrencyAggregator::class,
    ],
];
