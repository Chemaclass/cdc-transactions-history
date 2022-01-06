# CDC Transactions History

[![CI](https://github.com/Chemaclass/cdc-transactions-history/actions/workflows/ci.yml/badge.svg)](https://github.com/Chemaclass/cdc-transactions-history/actions/workflows/ci.yml)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Chemaclass/cdc-transactions-history/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Chemaclass/cdc-transactions-history/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Chemaclass/cdc-transactions-history/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Chemaclass/cdc-transactions-history/?branch=master)
[![Psalm Type-coverage Status](https://shepherd.dev/github/Chemaclass/cdc-transactions-history/coverage.svg)](https://shepherd.dev/github/Chemaclass/cdc-transactions-history)
[![MIT Software License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

This is a pet project to gather interesting data from the resulting export file of the history transactions from the
Crypto.com app. For example, how much investment have you put into a particular ticker.

### Why?

I used the "Crypto.com App" for learning purposes, and I would like to read the history as a csv that you can download
with all your transactions, and from it, I would like to extract and aggregate some data as I want.

For this reason I created this project, to be able to read that file and get the data I am interested with, while
practicing TDD and some software architecture decisions just for fun.

### How does it work

1. Add your `transactions.csv` file inside the `data/` folder.
2. The mapping of the transaction managers by kind is in `TransactionsHistoryFactory::createTransactionManagers()`.

```php
private function createTransactionManagers(): array
{
    return [
        TransactionKind::VIBAN_PURCHASE => new VIbanPurchaseTransactionManager(),
        // ... etc
    ];
}
```

In this function you can map what transaction manager do you want to use for each transaction kind.

### Commands

- [stats](src/TransactionsHistory/Infrastructure/Command/StatisticsCommand.php):
    - `php bin/console stats data/transactions.csv --kind=viban_purchase --ticker=ETH,ADA`
    - Options
        - `kind`: filter by transaction kind
        - `ticker`: filter by ticker

---

#### Future ideas

- Other "transaction managers" for other transaction kinds.
- Maybe allow multiple "transaction managers" for one transaction kind.
- ...
