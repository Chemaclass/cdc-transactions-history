<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\Transfer;

interface TransactionKind
{
    public const CARD_CASHBACK_REVERTED = 'card_cashback_reverted';

    public const CRYPTO_DEPOSIT = 'crypto_deposit';

    public const CRYPTO_EARN_INTEREST_PAID = 'crypto_earn_interest_paid';

    public const CRYPTO_EARN_PROGRAM_CREATED = 'crypto_earn_program_created';

    public const CRYPTO_EARN_PROGRAM_WITHDRAWN = 'crypto_earn_program_withdrawn';

    public const CRYPTO_EXCHANGE = 'crypto_exchange';

    public const CRYPTO_PAYMENT = 'crypto_payment';

    public const CRYPTO_PURCHASE = 'crypto_purchase';

    public const CRYPTO_TO_EXCHANGE_TRANSFER = 'crypto_to_exchange_transfer';

    public const CRYPTO_VIBAN_EXCHANGE = 'crypto_viban_exchange';

    public const CRYPTO_WITHDRAWAL = 'crypto_withdrawal';

    public const DUST_CONVERSION_CREDITED = 'dust_conversion_credited';

    public const DUST_CONVERSION_DEBITED = 'dust_conversion_debited';

    public const EXCHANGE_TO_CRYPTO_TRANSFER = 'exchange_to_crypto_transfer';

    public const LOCKUP_LOCK = 'lockup_lock';

    public const LOCKUP_UPGRADE = 'lockup_upgrade';

    public const MCO_STAKE_REWARD = 'mco_stake_reward';

    public const MOBILE_AIRTIME_REWARD = 'mobile_airtime_reward';

    public const REFERRAL_BONUS = 'referral_bonus';

    public const REFERRAL_CARD_CASHBACK = 'referral_card_cashback';

    public const REIMBURSEMENT = 'reimbursement';

    public const REWARDS_PLATFORM_DEPOSIT_CREDITED = 'rewards_platform_deposit_credited';

    public const SUPERCHARGER_DEPOSIT = 'supercharger_deposit';

    public const SUPERCHARGER_REWARD_TO_APP_CREDITED = 'supercharger_reward_to_app_credited';

    public const SUPERCHARGER_WITHDRAWAL = 'supercharger_withdrawal';

    public const VIBAN_PURCHASE = 'viban_purchase';
}
