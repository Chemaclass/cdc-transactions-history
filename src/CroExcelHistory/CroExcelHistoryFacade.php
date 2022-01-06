<?php

declare(strict_types=1);

namespace App\CroExcelHistory;

use App\CroExcelHistory\Infrastructure\Command\StatisticsCommand;
use Gacela\Framework\AbstractFacade;

/**
 * @method CroExcelHistoryFactory getFactory()
 */
final class CroExcelHistoryFacade extends AbstractFacade
{
    public function getStatisticsCommand(): StatisticsCommand
    {
        return $this->getFactory()->createStatisticsCommand();
    }

    /**
     * @return array<string,array<string,mixed>>
     */
    public function calculateStatistics(string $filepath): array
    {
        return $this->getFactory()
            ->createStatisticsService()
            ->forFilepath($filepath);
    }
}
