<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Infrastructure\Command\StatisticsCommand;
use Gacela\Framework\AbstractFacade;

/**
 * @method TransactionsHistoryFactory getFactory()
 */
final class TransactionsHistoryFacade extends AbstractFacade
{
    public function getStatisticsCommand(): StatisticsCommand
    {
        return $this->getFactory()->createStatisticsCommand();
    }
}
