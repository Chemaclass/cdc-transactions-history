<?php

declare(strict_types=1);

namespace App\CroExcelHistory\Domain\Transfer;

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

    public function getTimestampUtc(): string
    {
        return $this->timestampUtc;
    }

    public function setTimestampUtc(string $timestampUtc): self
    {
        $this->timestampUtc = $timestampUtc;

        return $this;
    }

    public function getTransactionDescription(): string
    {
        return $this->transactionDescription;
    }

    public function setTransactionDescription(string $transactionDescription): self
    {
        $this->transactionDescription = $transactionDescription;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getToCurrency(): string
    {
        return $this->toCurrency;
    }

    public function setToCurrency(string $toCurrency): self
    {
        $this->toCurrency = $toCurrency;

        return $this;
    }

    public function getToAmount(): float
    {
        return $this->toAmount;
    }

    public function setToAmount(float $toAmount): self
    {
        $this->toAmount = $toAmount;

        return $this;
    }

    public function getNativeCurrency(): string
    {
        return $this->nativeCurrency;
    }

    public function setNativeCurrency(string $nativeCurrency): self
    {
        $this->nativeCurrency = $nativeCurrency;

        return $this;
    }

    public function getNativeAmount(): float
    {
        return $this->nativeAmount;
    }

    public function setNativeAmount(float $nativeAmount): self
    {
        $this->nativeAmount = $nativeAmount;

        return $this;
    }

    public function getTransactionKind(): string
    {
        return $this->transactionKind;
    }

    public function setTransactionKind(string $transactionKind): self
    {
        $this->transactionKind = $transactionKind;

        return $this;
    }
}
