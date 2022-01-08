<?php

declare(strict_types=1);

namespace App\TransactionsHistory;

use App\TransactionsHistory\Domain\IO\FileReaderServiceInterface;
use App\TransactionsHistory\Domain\Mapper\TransactionMapperInterface;
use App\TransactionsHistory\Domain\Service\StatisticsService;
use App\TransactionsHistory\Domain\TransactionAggregator\CurrencyAggregator;
use App\TransactionsHistory\Domain\TransactionAggregator\ToCurrencyAggregator;
use App\TransactionsHistory\Domain\Transfer\TransactionAggregators;
use App\TransactionsHistory\Domain\Transfer\TransactionKind;
use App\TransactionsHistory\Infrastructure\Command\StatisticsCommand;
use App\TransactionsHistory\Infrastructure\IO\CsvReaderService;
use App\TransactionsHistory\Infrastructure\Mapper\CsvHeadersTransactionMapper;
use Gacela\Framework\AbstractFactory;

/**
 * @method TransactionsHistoryConfig getConfig()
 */
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
        $currencyAggregator = $this->createCurrencyAggregator();
        $toCurrencyAggregator = $this->createToCurrencyAggregator();

        return (new TransactionAggregators())
            ->put(TransactionKind::CARD_CASHBACK_REVERTED, $currencyAggregator)
            ->put(TransactionKind::CRYPTO_DEPOSIT, $currencyAggregator)
            ->put(TransactionKind::CRYPTO_EARN_INTEREST_PAID, $currencyAggregator)
            ->put(TransactionKind::CRYPTO_EARN_PROGRAM_CREATED, $currencyAggregator)
            ->put(TransactionKind::CRYPTO_EARN_PROGRAM_WITHDRAWN, $currencyAggregator)
            ->put(TransactionKind::CRYPTO_EXCHANGE, $toCurrencyAggregator)
            ->put(TransactionKind::CRYPTO_PAYMENT, $toCurrencyAggregator)
            ->put(TransactionKind::CRYPTO_PURCHASE, $currencyAggregator)
            ->put(TransactionKind::CRYPTO_TO_EXCHANGE_TRANSFER, $currencyAggregator)
            ->put(TransactionKind::CRYPTO_VIBAN_EXCHANGE, $toCurrencyAggregator)
            ->put(TransactionKind::CRYPTO_WITHDRAWAL, $currencyAggregator)
            ->put(TransactionKind::DUST_CONVERSION_CREDITED, $currencyAggregator)
            ->put(TransactionKind::DUST_CONVERSION_DEBITED, $currencyAggregator)
            ->put(TransactionKind::EXCHANGE_TO_CRYPTO_TRANSFER, $currencyAggregator)
            ->put(TransactionKind::LOCKUP_LOCK, $currencyAggregator)
            ->put(TransactionKind::LOCKUP_UPGRADE, $currencyAggregator)
            ->put(TransactionKind::MCO_STAKE_REWARD, $currencyAggregator)
            ->put(TransactionKind::MOBILE_AIRTIME_REWARD, $currencyAggregator)
            ->put(TransactionKind::REFERRAL_BONUS, $currencyAggregator)
            ->put(TransactionKind::REFERRAL_CARD_CASHBACK, $currencyAggregator)
            ->put(TransactionKind::REIMBURSEMENT, $currencyAggregator)
            ->put(TransactionKind::REWARDS_PLATFORM_DEPOSIT_CREDITED, $currencyAggregator)
            ->put(TransactionKind::SUPERCHARGER_DEPOSIT, $currencyAggregator)
            ->put(TransactionKind::SUPERCHARGER_REWARD_TO_APP_CREDITED, $currencyAggregator)
            ->put(TransactionKind::SUPERCHARGER_WITHDRAWAL, $currencyAggregator)
            ->put(TransactionKind::VIBAN_PURCHASE, $toCurrencyAggregator);
    }

    private function createCurrencyAggregator(): CurrencyAggregator
    {
        return new CurrencyAggregator(
            $this->getConfig()->getTotalDecimals(),
            $this->getConfig()->getTotalNativeDecimals()
        );
    }

    private function createToCurrencyAggregator(): ToCurrencyAggregator
    {
        return new ToCurrencyAggregator(
            $this->getConfig()->getTotalDecimals(),
            $this->getConfig()->getTotalNativeDecimals()
        );
    }
}
