<?php

declare(strict_types=1);

namespace App\CroExcelHistory;

use App\CroExcelHistory\Service\GroupedTransactions;
use App\CroExcelHistory\Service\StatisticsService;
use App\CroExcelHistory\Service\TransactionMapper;
use App\CroExcelHistory\TransactionManager\TransactionManagerInterface;
use App\CroExcelHistory\TransactionManager\VIbanPurchaseTransactionManager;
use App\CroExcelHistory\Transfer\TransactionKind;
use Gacela\Framework\AbstractFactory;

final class CroExcelHistoryFactory extends AbstractFactory
{
    public function createStatisticsService(): StatisticsService
    {
        return new StatisticsService(
            $this->createGroupedTransactions(),
            $this->createTransactionMapper(),
            $this->createTransactionManagers()
        );
    }

    private function createGroupedTransactions(): GroupedTransactions
    {
        return new GroupedTransactions();
    }

    private function createTransactionMapper(): TransactionMapper
    {
        return new TransactionMapper();
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
