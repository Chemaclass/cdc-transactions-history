<?php

declare(strict_types=1);

namespace App\CroExcelHistory;

use Gacela\Framework\AbstractConfig;

final class CroExcelHistoryConfig extends AbstractConfig
{
    public const TRANSACTION_FILENAME = 'transaction-filename';

    public function getTransactionFilename(): string
    {
        return (string) $this->get(self::TRANSACTION_FILENAME);
    }
}
