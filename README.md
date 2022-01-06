# CRO Excel History

[![MIT Software License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

This is a pet project to calculate interesting data from the resulting export file of the history transactions from the
Crypto.com app. For example, how much investment have you put into a particular ticker 

### Commands

- [stats](src/CroExcelHistory/Infrastructure/Command/StatisticsCommand.php):
    - `php bin/console stats --kind=viban_purchase --ticker=ETH`
    - Options
        - `kind`: filter by transaction kind
        - `ticker`: filter by ticker

## How does it work

1. Add your transactions.csv file inside the `data/` folder.
2. You can see the mapping of the transaction managers by kind in `CroExcelHistoryFactory::createTransactionManagers()`.
