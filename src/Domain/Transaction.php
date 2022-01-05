<?php

declare(strict_types=1);

namespace App\Domain;

use JetBrains\PhpStorm\Pure;

/**
 * @psalm-immutable
 */
final class Transaction
{
    public string $timestampUtc;
    public string $transactionDescription;
    public string $currency;
    public float $amount;
    public string $toCurrency;
    public float $toAmount;
    public string $nativeCurrency;
    public float $nativeAmount;
    public float $nativeAmountInUsd;
    public string $transactionKind;

    #[Pure]
    public static function fromArray(array $array): self
    {
        $self = new self();
        $self->timestampUtc = (string)($array['Timestamp (UTC)'] ?? '');
        $self->transactionDescription = (string)($array['Transaction Description'] ?? '');
        $self->currency = (string)($array['Currency'] ?? '');
        $self->amount = (float)($array['Amount'] ?? '');
        $self->toCurrency = (string)($array['To Currency'] ?? '');
        $self->toAmount = (float)($array['To Amount'] ?? '');
        $self->nativeCurrency = (string)($array['Native Currency'] ?? '');
        $self->nativeAmount = (float)($array['Native Amount'] ?? '');
        $self->nativeAmountInUsd = (float)($array['Native Amount (in USD)'] ?? '');
        $self->transactionKind = (string)($array['Transaction Kind'] ?? '');

        return $self;
    }
}
