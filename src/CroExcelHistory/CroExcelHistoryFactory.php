<?php

declare(strict_types=1);

namespace App\CroExcelHistory;

use App\CroExcelHistory\Mapper\CsvHeadersTransactionMapper;
use App\CroExcelHistory\Mapper\TransactionMapperInterface;
use App\CroExcelHistory\Service\StatisticsService;
use App\CroExcelHistory\TransactionManager\TransactionManagerInterface;
use App\CroExcelHistory\TransactionManager\VIbanPurchaseTransactionManager;
use App\CroExcelHistory\Transfer\TransactionKind;
use Gacela\Framework\AbstractFactory;

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
