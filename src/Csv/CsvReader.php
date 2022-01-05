<?php

declare(strict_types=1);

namespace App\Csv;

final class CsvReader
{
    /**
     * @return array<string,list<string>>
     */
    public function read(string $filePath): array
    {
        $csv = array_map("str_getcsv", file($filePath, FILE_SKIP_EMPTY_LINES));
        $keys = array_shift($csv);

        foreach ($csv as $i => $row) {
            $csv[$i] = array_combine($keys, $row);
        }

        return $csv;
    }
}