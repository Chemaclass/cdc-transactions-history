<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Domain\IO\FileReaderServiceInterface;
use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Service\AggregateService;
use App\TransactionsHistory\Domain\Service\TransactionsFilter;
use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use App\TransactionsHistory\Domain\Transfer\TransactionAggregators;
use App\TransactionsHistory\Infrastructure\Command\AggregateTransactionsCommand;
use App\TransactionsHistory\Infrastructure\Command\TransactionTypesCommand;
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
            $this->createAggregateService(),
            $this->createTransactionFilter()
        );
    }

    public function createTransactionTypesCommand(): TransactionTypesCommand
    {
        return new TransactionTypesCommand(
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

    private function createTransactionFilter(): TransactionsFilter
    {
        return new TransactionsFilter();
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
        return (new TransactionAggregators())
            ->add($this->createCurrencyAggregator())
            ->add($this->createToCurrencyAggregator());
    }

    private function createCurrencyAggregator(): CurrencyAggregator
    {
        return new CurrencyAggregator(
            $this->getConfig()->getTotalDecimals(),
            $this->getConfig()->getTotalNativeDecimals(),
            $this->getConfig()->getNativeCurrencyKey(),
        );
    }

    private function createToCurrencyAggregator(): ToCurrencyAggregator
    {
        return new ToCurrencyAggregator(
            $this->getConfig()->getTotalDecimals(),
            $this->getConfig()->getTotalNativeDecimals(),
            $this->getConfig()->getNativeCurrencyKey(),
        );
    }
}
