<?php

declare(strict_types=1);

namespace App\CroExcelHistory;

use App\CroExcelHistory\Domain\Mapper\CsvHeadersTransactionMapper;
use App\CroExcelHistory\Domain\Mapper\TransactionMapperInterface;
use App\CroExcelHistory\Domain\Service\StatisticsService;
use App\CroExcelHistory\Domain\TransactionManager\TransactionManagerInterface;
use App\CroExcelHistory\Domain\TransactionManager\VIbanPurchaseTransactionManager;
use App\CroExcelHistory\Domain\Transfer\TransactionKind;
use Gacela\Framework\AbstractFactory;

/**
 * @method CroExcelHistoryConfig getConfig()
 */
final class CroExcelHistoryFactory extends AbstractFactory
{
    public function createStatisticsService(): StatisticsService
    {
        return new StatisticsService(
            $this->createTransactionMapper(),
            $this->createTransactionManagers()
        );
    }

    private function createTransactionMapper(): TransactionMapperInterface
    {
        return new CsvHeadersTransactionMapper();
    }

    /**
     * @return array<string,TransactionManagerInterface>
     */
    private function createTransactionManagers(): array
    {
        return [
            TransactionKind::VIBAN_PURCHASE => new VIbanPurchaseTransactionManager(),
        ];
    }
}
