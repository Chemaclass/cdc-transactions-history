<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Mapper;

use App\CroExcelHistory\Transfer\Transaction;

interface TransactionMapperInterface
{
    /**
     * @param array<string,string> $array
     */
    public function map(array $array): Transaction;
}
