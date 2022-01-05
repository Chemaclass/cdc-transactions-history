<?php

declare(strict_types=1);

namespace App\Csv;

use Safe\Exceptions\ArrayException;
use Safe\Exceptions\FilesystemException;
use function Safe\array_combine;
use function Safe\file;

final class CsvReader
{
    /**
     * @throws ArrayException|FilesystemException
     *
     * @return list<array<string,string>>
     *
     * @psalm-suppress InvalidReturnType, InvalidReturnStatement
     */
    public function read(string $filePath): array
    {
        $csv = array_map('str_getcsv', file($filePath, FILE_SKIP_EMPTY_LINES));
        /** @var array<string,string> $keys */
        $keys = array_shift($csv);

        foreach ($csv as $i => $row) {
            $csv[$i] = array_combine($keys, $row);
        }

        return $csv;
    }
}
