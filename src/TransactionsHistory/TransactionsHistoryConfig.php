<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use Gacela\Framework\AbstractConfig;

final class TransactionsHistoryConfig extends AbstractConfig
{
    public const TOTAL_DECIMALS = 'TransactionsHistory::TOTAL_DECIMALS';

    public const TOTAL_NATIVE_DECIMALS = 'TransactionsHistory::TOTAL_NATIVE_DECIMALS';

    public const NATIVE_CURRENCY_KEY = 'TransactionsHistory::NATIVE_CURRENCY_KEY';

    public const TRANSACTION_KIND_AGGREGATORS = 'TransactionsHistory::TRANSACTION_KIND_AGGREGATORS';

    public function getTotalDecimals(): int
    {
        return (int) $this->get(self::TOTAL_DECIMALS, 6);// @phpstan-ignore-line
    }

    public function getTotalNativeDecimals(): int
    {
        return (int) $this->get(self::TOTAL_NATIVE_DECIMALS, 2);// @phpstan-ignore-line
    }

    public function getNativeCurrencyKey(): string
    {
        return (string) $this->get(self::NATIVE_CURRENCY_KEY);// @phpstan-ignore-line
    }

    /**
     * @return array<string,class-string>
     */
    public function getTransactionKindAggregators(): array
    {
        return (array) $this->get(self::TRANSACTION_KIND_AGGREGATORS);// @phpstan-ignore-line
    }
}
