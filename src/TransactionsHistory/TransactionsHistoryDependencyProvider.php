<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use Gacela\Framework\AbstractDependencyProvider;
use Gacela\Framework\Container\Container;

/**
 * @method TransactionsHistoryConfig getConfig()
 */
final class TransactionsHistoryDependencyProvider extends AbstractDependencyProvider
{
    public function provideModuleDependencies(Container $container): void
    {
        $this->addCurrencyAggregator($container);
        $this->addToCurrencyAggregator($container);
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
