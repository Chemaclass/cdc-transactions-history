<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Infrastructure\Mapper;

use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Transfer\Transaction;

final class CsvHeadersTransactionMapper implements TransactionMapperInterface
{
    private const DECIMAL_SEPARATOR = '.';

    private const THOUSANDS_SEPARATOR = '';

    private const DECIMALS = 20;

    /**
     * @param array<string,string> $array
     */
    public function map(array $array): Transaction
    {
        return (new Transaction())
            ->setTimestampUtc($array['Timestamp (UTC)'] ?? '')
            ->setTransactionDescription($array['Transaction Description'] ?? '')
            ->setCurrency($array['Currency'] ?? '')
            ->setAmount($this->asFloat(($array['Amount'] ?? '')))
            ->setToCurrency($array['To Currency'] ?? '')
            ->setToAmount($this->asFloat($array['To Amount'] ?? ''))
            ->setNativeCurrency($array['Native Currency'] ?? '')
            ->setNativeAmount($this->asFloat($array['Native Amount'] ?? ''))
            ->setNativeAmountInUSD($this->asFloat($array['Native Amount (in USD)'] ?? ''))
            ->setTransactionType($array['Transaction Kind'] ?? '');
    }

    private function asFloat(string $number): float
    {
        $float = number_format((float) $number, self::DECIMALS, self::DECIMAL_SEPARATOR, self::THOUSANDS_SEPARATOR);

        return (float) rtrim(rtrim($float, '0'), self::DECIMAL_SEPARATOR);
    }
}
