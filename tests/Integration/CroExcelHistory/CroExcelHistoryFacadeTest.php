<?php

declare(strict_types=1);

namespace Tests\Integration\CroExcelHistory;

use App\CroExcelHistory\CroExcelHistoryFacade;
use App\CroExcelHistory\Transfer\TransactionKind;
use PHPUnit\Framework\TestCase;

final class CroExcelHistoryFacadeTest extends TestCase
{
    private CroExcelHistoryFacade $facade;

    public function setUp(): void
    {
        $this->facade = new CroExcelHistoryFacade();
    }

    public function test_statistics_by_kind(): void
    {
        $csv = [
            [
                'Currency' => 'EUR',
                'Amount' => '-101.11',
                'To Currency' => 'BCH',
                'To Amount' => '1',
                'Native Currency' => 'EUR',
                'Native Amount' => '101.11',
                'Transaction Kind' => TransactionKind::VIBAN_PURCHASE,
            ],
            [
                'Currency' => 'EUR',
                'Amount' => '-102.22',
                'To Currency' => 'BCH',
                'To Amount' => '1',
                'Native Currency' => 'EUR',
                'Native Amount' => '102.22',
                'Transaction Kind' => TransactionKind::VIBAN_PURCHASE,
            ],
            [
                'Currency' => 'EUR',
                'Amount' => '-202.22',
                'To Currency' => 'ADA',
                'To Amount' => '2',
                'Native Currency' => 'EUR',
                'Native Amount' => '202.22',
                'Transaction Kind' => TransactionKind::VIBAN_PURCHASE,
            ],
            [
                'Currency' => 'EUR',
                'Amount' => '-303.33',
                'To Currency' => 'DOT',
                'To Amount' => '3',
                'Native Currency' => 'EUR',
                'Native Amount' => '303.33',
                'Transaction Kind' => TransactionKind::CRYPTO_WITHDRAWAL,
            ],
        ];

        $expected = [
            TransactionKind::VIBAN_PURCHASE => [
                'BCH' => [
                    'totalInEuros' => 203.33,
                ],
                'ADA' => [
                    'totalInEuros' => 202.22,
                ],
            ],
            TransactionKind::CRYPTO_WITHDRAWAL => [], # TODO: empty because it's not implemented yet
        ];

        $actual = $this->facade->statisticsByKind($csv);

        self::assertEquals($expected, $actual);
    }
}
