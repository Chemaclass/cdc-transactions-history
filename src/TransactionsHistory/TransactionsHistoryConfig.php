<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use Gacela\Framework\AbstractConfig;

final class TransactionsHistoryConfig extends AbstractConfig
{
    public const TOTAL_DECIMALS = 'TransactionsHistory::TOTAL_DECIMALS';

    public const TOTAL_NATIVE_DECIMALS = 'TransactionsHistory::TOTAL_NATIVE_DECIMALS';

    public function getTotalDecimals(): int
    {
        return (int) $this->get(self::TOTAL_DECIMALS, 6);// @phpstan-ignore-line
    }

    public function getTotalNativeDecimals(): int
    {
        return (int) $this->get(self::TOTAL_NATIVE_DECIMALS, 2);// @phpstan-ignore-line
    }
}
