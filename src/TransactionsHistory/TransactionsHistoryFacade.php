<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Infrastructure\Command\AggregateTransactionsCommand;
use App\TransactionsHistory\Infrastructure\Command\TransactionTypesCommand;
use Gacela\Framework\AbstractFacade;

/**
 * @method TransactionsHistoryFactory getFactory()
 */
final class TransactionsHistoryFacade extends AbstractFacade
{
    public function getAggregateTransactionsCommand(): AggregateTransactionsCommand
    {
        return $this->getFactory()->createAggregateTransactionsCommand();
    }

    public function getTransactionTypesCommand(): TransactionTypesCommand
    {
        return $this->getFactory()->createTransactionTypesCommand();
    }
}
