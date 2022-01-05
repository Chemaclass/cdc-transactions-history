<?php

declare(strict_types=1);

namespace App\CroExcelHistory;

use Gacela\Framework\AbstractFacade;

/**
 * @method CroExcelHistoryFactory getFactory()
 */
final class CroExcelHistoryFacade extends AbstractFacade
{
    /**
     * @param list<array<string,string>> $csv
     *
     * @return array<string,array<string,mixed>>
     */
    public function statisticsByKind(array $csv): array
    {
        return $this->getFactory()
            ->createStatisticsService()
            ->forCsv($csv);
    }
}
