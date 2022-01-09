<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\TransactionAggregatorInterface;
use App\TransactionsHistory\Domain\Transfer\TransactionAggregators;
use Gacela\Framework\AbstractDependencyProvider;
use Gacela\Framework\Container\Container;

/**
 * @method TransactionsHistoryConfig getConfig()
 */
final class TransactionsHistoryDependencyProvider extends AbstractDependencyProvider
{
    public function provideModuleDependencies(Container $container): void
    {
        $this->addTransactionAggregators($container);
        $this->addCurrencyAggregator($container);
        $this->addToCurrencyAggregator($container);
    }

    /**
     * This way, when the Factory needs to build the TransactionAggregators it will be build once and store as a
     * singleton in the DependencyProvider.
     */
    private function addTransactionAggregators(Container $container): void
    {
        $container->set(TransactionAggregators::class, function(Container $container): TransactionAggregators {
            $aggregators = new TransactionAggregators();

            foreach ($this->getConfig()->getTransactionKindAggregators() as $kind => $aggregatorClassName) {
                /** @var TransactionAggregatorInterface $aggregator */
                $aggregator = $container->get($aggregatorClassName);
                $aggregators->put($kind, $aggregator);
            }

            return $aggregators;
        });
    }

    private function addCurrencyAggregator(Container $container): void
    {
        $container->set(CurrencyAggregator::class, fn() => new CurrencyAggregator(
            $this->getConfig()->getTotalDecimals(),
            $this->getConfig()->getNativeCurrencyKey(),
            $this->getConfig()->getTotalNativeDecimals()
        ));
    }

    private function addToCurrencyAggregator(Container $container): void
    {
        $container->set(ToCurrencyAggregator::class, fn() => new ToCurrencyAggregator(
            $this->getConfig()->getTotalDecimals(),
            $this->getConfig()->getNativeCurrencyKey(),
            $this->getConfig()->getTotalNativeDecimals()
        ));
    }
}
