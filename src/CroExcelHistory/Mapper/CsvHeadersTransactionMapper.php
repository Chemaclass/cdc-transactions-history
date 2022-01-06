<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Mapper;

use App\CroExcelHistory\Transfer\Transaction;

final class CsvHeadersTransactionMapper implements TransactionMapperInterface
{
    /**
     * @param array<string,string> $array
     */
    public function map(array $array): Transaction
    {
        return (new Transaction())
            ->setTimestampUtc($array['Timestamp (UTC)'] ?? '')
            ->setTransactionDescription($array['Transaction Description'] ?? '')
            ->setCurrency($array['Currency'] ?? '')
            ->setAmount((float) ($array['Amount'] ?? ''))
            ->setToCurrency($array['To Currency'] ?? '')
            ->setToAmount((float) ($array['To Amount'] ?? ''))
            ->setNativeCurrency($array['Native Currency'] ?? '')
            ->setNativeAmount((float) ($array['Native Amount'] ?? ''))
            ->setTransactionKind($array['Transaction Kind'] ?? '');
    }
}
