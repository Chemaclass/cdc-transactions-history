# CRO Excel History

[![MIT Software License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

This is a pet project to calculate interesting data from the resulting export file of the history transactions from the
Crypto.com app.

## Example

The `example/cli.php` script shows you an example of how does it works.

## How does it work

1. Add your transactions.csv file inside the data/ folder.
2. You can see the mapping of the transaction managers by kind in `CroExcelHistoryFactory::createTransactionManagers()`.
