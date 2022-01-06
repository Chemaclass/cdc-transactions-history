<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Domain\Mapper;

use App\CroExcelHistory\Domain\Transfer\Transaction;

interface TransactionMapperInterface
{
    /**
     * @param array<string,string> $array
     */
    public function map(array $array): Transaction;
}
