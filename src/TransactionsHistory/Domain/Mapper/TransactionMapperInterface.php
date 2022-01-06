<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\Mapper;

use App\TransactionsHistory\Domain\Transfer\Transaction;

interface TransactionMapperInterface
{
    /**
     * @param array<string,string> $array
     */
    public function map(array $array): Transaction;
}
