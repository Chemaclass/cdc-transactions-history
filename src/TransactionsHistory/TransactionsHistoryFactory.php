<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Domain\IO\FileReaderServiceInterface;
use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Service\AggregateService;
use App\TransactionsHistory\Domain\TransactionAggregator\TransactionAggregatorInterface;
use App\TransactionsHistory\Domain\Transfer\TransactionAggregators;
use App\TransactionsHistory\Infrastructure\Command\AggregateTransactionsCommand;
use App\TransactionsHistory\Infrastructure\IO\CsvReaderService;
use App\TransactionsHistory\Infrastructure\Mapper\CsvHeadersTransactionMapper;
use Gacela\Framework\AbstractFactory;

/**
 * @method TransactionsHistoryConfig getConfig()
 */
final class TransactionsHistoryFactory extends AbstractFactory
{
    public function createStatisticsCommand(): AggregateTransactionsCommand
    {
        return new AggregateTransactionsCommand(
            $this->createAggregateService()
        );
    }

    private function createAggregateService(): AggregateService
    {
        return new AggregateService(
            $this->createFileReaderService(),
            $this->createTransactionMapper(),
            $this->createTransactionAggregators()
        );
    }

    private function createFileReaderService(): FileReaderServiceInterface
    {
        return new CsvReaderService();
    }

    private function createTransactionMapper(): TransactionMapperInterface
    {
        return new CsvHeadersTransactionMapper();
    }

    private function createTransactionAggregators(): TransactionAggregators
    {
        $aggregators = new TransactionAggregators();

        foreach ($this->getConfig()->getTransactionKindAggregators() as $kind => $aggregatorName) {
            /** @var TransactionAggregatorInterface $aggregator */
            $aggregator = $this->getProvidedDependency($aggregatorName);
            $aggregators->put($kind, $aggregator);
        }

        return $aggregators;
    }
}
