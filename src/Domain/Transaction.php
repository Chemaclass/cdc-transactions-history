<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * @psalm-immutable
 */
final class Transaction
{
    private string $timestampUtc = '';

    private string $transactionDescription = '';

    private string $currency = '';

    private float $amount = 0.0;

    private string $toCurrency = '';

    private float $toAmount = 0.0;

    private string $nativeCurrency = '';

    private float $nativeAmount = 0.0;

    private string $transactionKind = '';

    /**
     * @param array{
     *     "Timestamp (UTC)": string|null,
     *     "Transaction Description": string|null,
     *     "Currency": string|null,
     *     "Amount": string|null,
     *     "To Currency": string|null,
     *     "To Amount": string|null,
     *     "Native Currency": string|null,
     *     "Native Amount": string|null,
     *     "Transaction Kind": string|null,
     * } $array
     */
    public static function fromArray(array $array): self
    {
        $self = new self();
        $self->timestampUtc = $array['Timestamp (UTC)'] ?? '';
        $self->transactionDescription = $array['Transaction Description'] ?? '';
        $self->currency = $array['Currency'] ?? '';
        $self->amount = (float) ($array['Amount'] ?? '');
        $self->toCurrency = $array['To Currency'] ?? '';
        $self->toAmount = (float) ($array['To Amount'] ?? '');
        $self->nativeCurrency = $array['Native Currency'] ?? '';
        $self->nativeAmount = (float) ($array['Native Amount'] ?? '');
        $self->transactionKind = $array['Transaction Kind'] ?? '';

        return $self;
    }

    public function timestampUtc(): string
    {
        return $this->timestampUtc;
    }

    public function transactionDescription(): string
    {
        return $this->transactionDescription;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function toCurrency(): string
    {
        return $this->toCurrency;
    }

    public function toAmount(): float
    {
        return $this->toAmount;
    }

    public function nativeCurrency(): string
    {
        return $this->nativeCurrency;
    }

    public function nativeAmount(): float
    {
        return $this->nativeAmount;
    }

    public function transactionKind(): string
    {
        return $this->transactionKind;
    }
}
