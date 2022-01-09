<?php

declare(strict_types=1);

use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use App\TransactionsHistory\Domain\Transfer\TransactionKind;
use App\TransactionsHistory\TransactionsHistoryConfig;

return [
    TransactionsHistoryConfig::TOTAL_DECIMALS => 8,
    TransactionsHistoryConfig::TOTAL_NATIVE_DECIMALS => 2,
    TransactionsHistoryConfig::NATIVE_CURRENCY_KEY => 'EUR',
    TransactionsHistoryConfig::TRANSACTION_KIND_AGGREGATORS => [
        TransactionKind::CARD_CASHBACK_REVERTED => CurrencyAggregator::class,
        TransactionKind::CRYPTO_DEPOSIT => CurrencyAggregator::class,
        TransactionKind::CRYPTO_EARN_INTEREST_PAID => CurrencyAggregator::class,
        TransactionKind::CRYPTO_EARN_PROGRAM_CREATED => CurrencyAggregator::class,
        TransactionKind::CRYPTO_EARN_PROGRAM_WITHDRAWN => CurrencyAggregator::class,
        TransactionKind::CRYPTO_EXCHANGE => ToCurrencyAggregator::class,
        TransactionKind::CRYPTO_PAYMENT => ToCurrencyAggregator::class,
        TransactionKind::CRYPTO_PURCHASE => CurrencyAggregator::class,
        TransactionKind::CRYPTO_TO_EXCHANGE_TRANSFER => CurrencyAggregator::class,
        TransactionKind::CRYPTO_VIBAN_EXCHANGE => ToCurrencyAggregator::class,
        TransactionKind::CRYPTO_WITHDRAWAL => CurrencyAggregator::class,
        TransactionKind::DUST_CONVERSION_CREDITED => CurrencyAggregator::class,
        TransactionKind::DUST_CONVERSION_DEBITED => CurrencyAggregator::class,
        TransactionKind::EXCHANGE_TO_CRYPTO_TRANSFER => CurrencyAggregator::class,
        TransactionKind::LOCKUP_LOCK => CurrencyAggregator::class,
        TransactionKind::LOCKUP_UPGRADE => CurrencyAggregator::class,
        TransactionKind::MCO_STAKE_REWARD => CurrencyAggregator::class,
        TransactionKind::MOBILE_AIRTIME_REWARD => CurrencyAggregator::class,
        TransactionKind::REFERRAL_BONUS => CurrencyAggregator::class,
        TransactionKind::REFERRAL_CARD_CASHBACK => CurrencyAggregator::class,
        TransactionKind::REIMBURSEMENT => CurrencyAggregator::class,
        TransactionKind::REWARDS_PLATFORM_DEPOSIT_CREDITED => CurrencyAggregator::class,
        TransactionKind::SUPERCHARGER_DEPOSIT => CurrencyAggregator::class,
        TransactionKind::SUPERCHARGER_REWARD_TO_APP_CREDITED => CurrencyAggregator::class,
        TransactionKind::SUPERCHARGER_WITHDRAWAL => CurrencyAggregator::class,
        TransactionKind::VIBAN_PURCHASE => ToCurrencyAggregator::class,
    ],
];
