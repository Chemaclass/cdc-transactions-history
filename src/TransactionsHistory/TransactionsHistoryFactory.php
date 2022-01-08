<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Domain\IO\FileReaderServiceInterface;
use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Service\StatisticsService;
use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use App\TransactionsHistory\Domain\Transfer\TransactionKind;
use App\TransactionsHistory\Domain\Transfer\TransactionAggregators;
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
        return (new TransactionAggregators())
            ->put(TransactionKind::CARD_CASHBACK_REVERTED, new CurrencyAggregator())
            ->put(TransactionKind::CRYPTO_DEPOSIT, new CurrencyAggregator())
            ->put(TransactionKind::CRYPTO_EARN_INTEREST_PAID, new CurrencyAggregator())
            ->put(TransactionKind::CRYPTO_EARN_PROGRAM_CREATED, new CurrencyAggregator())
            ->put(TransactionKind::CRYPTO_EARN_PROGRAM_WITHDRAWN, new CurrencyAggregator())
            ->put(TransactionKind::CRYPTO_EXCHANGE, new ToCurrencyAggregator())
            ->put(TransactionKind::CRYPTO_PAYMENT, new ToCurrencyAggregator())
            ->put(TransactionKind::CRYPTO_PURCHASE, new CurrencyAggregator())
            ->put(TransactionKind::CRYPTO_TO_EXCHANGE_TRANSFER, new CurrencyAggregator())
            ->put(TransactionKind::CRYPTO_VIBAN_EXCHANGE, new ToCurrencyAggregator())
            ->put(TransactionKind::CRYPTO_WITHDRAWAL, new CurrencyAggregator())
            ->put(TransactionKind::DUST_CONVERSION_CREDITED, new CurrencyAggregator())
            ->put(TransactionKind::DUST_CONVERSION_DEBITED, new CurrencyAggregator())
            ->put(TransactionKind::EXCHANGE_TO_CRYPTO_TRANSFER, new CurrencyAggregator())
            ->put(TransactionKind::LOCKUP_LOCK, new CurrencyAggregator())
            ->put(TransactionKind::LOCKUP_UPGRADE, new CurrencyAggregator())
            ->put(TransactionKind::MCO_STAKE_REWARD, new CurrencyAggregator())
            ->put(TransactionKind::MOBILE_AIRTIME_REWARD, new CurrencyAggregator())
            ->put(TransactionKind::REFERRAL_BONUS, new CurrencyAggregator())
            ->put(TransactionKind::REFERRAL_CARD_CASHBACK, new CurrencyAggregator())
            ->put(TransactionKind::REIMBURSEMENT, new CurrencyAggregator())
            ->put(TransactionKind::REWARDS_PLATFORM_DEPOSIT_CREDITED, new CurrencyAggregator())
            ->put(TransactionKind::SUPERCHARGER_DEPOSIT, new CurrencyAggregator())
            ->put(TransactionKind::SUPERCHARGER_REWARD_TO_APP_CREDITED, new CurrencyAggregator())
            ->put(TransactionKind::SUPERCHARGER_WITHDRAWAL, new CurrencyAggregator())
            ->put(TransactionKind::VIBAN_PURCHASE, new ToCurrencyAggregator());
    }
}
