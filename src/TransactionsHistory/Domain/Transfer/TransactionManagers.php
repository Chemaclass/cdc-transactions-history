<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\Transfer;

use App\TransactionsHistory\Domain\TransactionManager\NullTransactionManager;
use App\TransactionsHistory\Domain\TransactionManager\TransactionManagerInterface;

final class TransactionManagers
{
    /** @var array<string,TransactionManagerInterface> */
    private array $transactionsManagers = [];

    public function add(string $transactionKind, TransactionManagerInterface $transactionManager): self
    {
        $this->transactionsManagers[$transactionKind] = $transactionManager;

        return $this;
    }

    public function get(string $kind): TransactionManagerInterface
    {
        return $this->transactionsManagers[$kind] ?? new NullTransactionManager();
    }
}
