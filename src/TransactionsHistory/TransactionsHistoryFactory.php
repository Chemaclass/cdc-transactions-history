<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Domain\IO\FileReaderServiceInterface;
use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Service\StatisticsService;
use App\TransactionsHistory\Domain\TransactionManager\CurrencyTransactionManager;
use App\TransactionsHistory\Domain\TransactionManager\ToCurrencyTransactionManager;
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
            ->add(TransactionKind::CARD_CASHBACK_REVERTED, new CurrencyTransactionManager())
            ->add(TransactionKind::CRYPTO_DEPOSIT, new CurrencyTransactionManager())
            ->add(TransactionKind::CRYPTO_EARN_INTEREST_PAID, new CurrencyTransactionManager())
            ->add(TransactionKind::CRYPTO_EARN_PROGRAM_CREATED, new CurrencyTransactionManager())
            ->add(TransactionKind::CRYPTO_EARN_PROGRAM_WITHDRAWN, new CurrencyTransactionManager())
            ->add(TransactionKind::CRYPTO_EXCHANGE, new ToCurrencyTransactionManager())
            ->add(TransactionKind::CRYPTO_PAYMENT, new ToCurrencyTransactionManager())
            ->add(TransactionKind::CRYPTO_PURCHASE, new CurrencyTransactionManager())
            ->add(TransactionKind::CRYPTO_TO_EXCHANGE_TRANSFER, new CurrencyTransactionManager())
            ->add(TransactionKind::CRYPTO_VIBAN_EXCHANGE, new ToCurrencyTransactionManager())
            ->add(TransactionKind::CRYPTO_WITHDRAWAL, new CurrencyTransactionManager())
            ->add(TransactionKind::DUST_CONVERSION_CREDITED, new CurrencyTransactionManager())
            ->add(TransactionKind::DUST_CONVERSION_DEBITED, new CurrencyTransactionManager())
            ->add(TransactionKind::EXCHANGE_TO_CRYPTO_TRANSFER, new CurrencyTransactionManager())
            ->add(TransactionKind::LOCKUP_LOCK, new CurrencyTransactionManager())
            ->add(TransactionKind::LOCKUP_UPGRADE, new CurrencyTransactionManager())
            ->add(TransactionKind::MCO_STAKE_REWARD, new CurrencyTransactionManager())
            ->add(TransactionKind::MOBILE_AIRTIME_REWARD, new CurrencyTransactionManager())
            ->add(TransactionKind::REFERRAL_BONUS, new CurrencyTransactionManager())
            ->add(TransactionKind::REFERRAL_CARD_CASHBACK, new CurrencyTransactionManager())
            ->add(TransactionKind::REIMBURSEMENT, new CurrencyTransactionManager())
            ->add(TransactionKind::REWARDS_PLATFORM_DEPOSIT_CREDITED, new CurrencyTransactionManager())
            ->add(TransactionKind::SUPERCHARGER_DEPOSIT, new CurrencyTransactionManager())
            ->add(TransactionKind::SUPERCHARGER_REWARD_TO_APP_CREDITED, new CurrencyTransactionManager())
            ->add(TransactionKind::SUPERCHARGER_WITHDRAWAL, new CurrencyTransactionManager())
            ->add(TransactionKind::VIBAN_PURCHASE, new ToCurrencyTransactionManager());
    }
}
