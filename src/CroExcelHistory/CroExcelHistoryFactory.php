<?php

declare(strict_types=1);

namespace App\CroExcelHistory;

use App\CroExcelHistory\Domain\Mapper\CsvHeadersTransactionMapper;
use App\CroExcelHistory\Domain\Mapper\TransactionMapperInterface;
use App\CroExcelHistory\Domain\Service\StatisticsService;
use App\CroExcelHistory\Domain\TransactionManager\TransactionManagerInterface;
use App\CroExcelHistory\Domain\TransactionManager\VIbanPurchaseTransactionManager;
use App\CroExcelHistory\Domain\Transfer\TransactionKind;
use App\CroExcelHistory\Infrastructure\Command\StatisticsCommand;
use App\CroExcelHistory\Infrastructure\IO\CsvReaderService;
use Gacela\Framework\AbstractFactory;

final class CroExcelHistoryFactory extends AbstractFactory
{
    public function createStatisticsCommand(): StatisticsCommand
    {
        return new StatisticsCommand(
            $this->createCsvReaderService(),
            $this->createStatisticsService()
        );
    }

    public function createStatisticsService(): StatisticsService
    {
        return new StatisticsService(
            $this->createTransactionMapper(),
            $this->createTransactionManagers()
        );
    }

    private function createCsvReaderService(): CsvReaderService
    {
        return new CsvReaderService();
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
