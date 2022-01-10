<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Domain\IO\FileReaderServiceInterface;
use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Service\AggregateService;
use App\TransactionsHistory\Domain\Transfer\TransactionAggregators;
use App\TransactionsHistory\Infrastructure\Command\AggregateTransactionsCommand;
use App\TransactionsHistory\Infrastructure\Command\TransactionKindsCommand;
use App\TransactionsHistory\Infrastructure\IO\CsvReaderService;
use App\TransactionsHistory\Infrastructure\Mapper\CsvHeadersTransactionMapper;
use Gacela\Framework\AbstractFactory;

/**
 * @method TransactionsHistoryConfig getConfig()
 */
final class TransactionsHistoryFactory extends AbstractFactory
{
    public function createAggregateTransactionsCommand(): AggregateTransactionsCommand
    {
        return new AggregateTransactionsCommand(
            $this->createAggregateService()
        );
    }

    public function createTransactionKindsCommand(): TransactionKindsCommand
    {
        return new TransactionKindsCommand(
            $this->createAggregateService()
        );
    }

    private function createAggregateService(): AggregateService
    {
        return new AggregateService(
            $this->createFileReaderService(),
            $this->createTransactionMapper(),
            $this->getTransactionAggregators()
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

    /**
     * Using the DependencyProvider as a singleton mechanism.
     * So the TransactionAggregators and its Aggregators are cached in the Container.
     */
    private function getTransactionAggregators(): TransactionAggregators
    {
        return $this->getProvidedDependency(TransactionAggregators::class);// @phpstan-ignore-line
    }
}
