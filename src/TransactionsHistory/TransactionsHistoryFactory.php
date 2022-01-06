<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Domain\Mapper\CsvHeadersTransactionMapper;
use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Service\StatisticsService;
use App\TransactionsHistory\Domain\TransactionManager\TransactionManagerInterface;
use App\TransactionsHistory\Domain\TransactionManager\VIbanPurchaseTransactionManager;
use App\TransactionsHistory\Domain\Transfer\TransactionKind;
use App\TransactionsHistory\Infrastructure\Command\StatisticsCommand;
use App\TransactionsHistory\Infrastructure\IO\CsvReaderService;
use Gacela\Framework\AbstractFactory;

final class TransactionsHistoryFactory extends AbstractFactory
{
    public function createStatisticsCommand(): StatisticsCommand
    {
        return new StatisticsCommand(
            $this->createStatisticsService()
        );
    }

    private function createStatisticsService(): StatisticsService
    {
        return new StatisticsService(
            $this->createCsvReaderService(),
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
