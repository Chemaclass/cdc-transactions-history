<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use Gacela\Framework\AbstractDependencyProvider;
use Gacela\Framework\Container\Container;

/**
 * @method TransactionsHistoryConfig getConfig()
 */
final class TransactionsHistoryDependencyProvider extends AbstractDependencyProvider
{
    public function provideModuleDependencies(Container $container): void
    {
    }
}
