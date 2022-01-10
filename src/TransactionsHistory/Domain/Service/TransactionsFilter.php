<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\Service;

final class TransactionsFilter
{
    /**
     * @param array<string,array<string,mixed>> $transactionsGroupedByType
     *
     * @return array<string,array<string,mixed>>
     */
    public function filterForGroupedByType(
        array $transactionsGroupedByType,
        ?string $inputType = null,
        ?string $inputCurrency = null
    ): array {
        $result = [];
        $types = ($inputType)
            ? explode(',', $inputType)
            : array_keys($transactionsGroupedByType);

        foreach ($transactionsGroupedByType as $type => $transactions) {
            if (!in_array($type, $types, true)) {
                continue;
            }

            $currencies = ($inputCurrency)
                ? explode(',', $inputCurrency)
                : array_keys($transactions);

            if (empty($currencies)) {
                continue;
            }

            foreach ($transactions as $currency => $transaction) {
                if (in_array($currency, $currencies, true)) {
                    $result[$type][$currency] = $transaction;
                }
            }
        }

        return $result;
    }
}
