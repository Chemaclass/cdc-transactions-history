<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Domain\IO\FileReaderServiceInterface;
use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Service\StatisticsService;
use App\TransactionsHistory\Domain\TransactionManager\CryptoWithdrawalTransactionManager;
use App\TransactionsHistory\Domain\TransactionManager\VIbanPurchaseTransactionManager;
use App\TransactionsHistory\Domain\Transfer\TransactionKind;
use App\TransactionsHistory\Domain\Transfer\TransactionManagers;
use App\TransactionsHistory\Infrastructure\Command\StatisticsCommand;
use App\TransactionsHistory\Infrastructure\IO\CsvReaderService;
use App\TransactionsHistory\Infrastructure\Mapper\CsvHeadersTransactionMapper;
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
            $this->createFileReaderService(),
            $this->createTransactionMapper(),
            $this->createTransactionManagers()
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

    private function createTransactionManagers(): TransactionManagers
    {
        return (new TransactionManagers())
            ->add(TransactionKind::VIBAN_PURCHASE, new VIbanPurchaseTransactionManager())
            ->add(TransactionKind::CRYPTO_WITHDRAWAL, new CryptoWithdrawalTransactionManager());
    }
}
