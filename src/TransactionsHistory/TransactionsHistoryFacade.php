<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Infrastructure\Command\AggregateTransactionsCommand;
use Gacela\Framework\AbstractFacade;

/**
 * @method TransactionsHistoryFactory getFactory()
 */
final class TransactionsHistoryFacade extends AbstractFacade
{
    public function getAggregateTransactionsCommand(): AggregateTransactionsCommand
    {
        return $this->getFactory()->createStatisticsCommand();
    }
}
