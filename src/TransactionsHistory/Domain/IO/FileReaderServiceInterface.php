<?php

declare(strict_types=1);

namespace App\TransactionsHistory\Domain\IO;

interface FileReaderServiceInterface
{
    /**
     * @return list<array<string,string>>
     */
    public function read(string $filePath): array;
}
