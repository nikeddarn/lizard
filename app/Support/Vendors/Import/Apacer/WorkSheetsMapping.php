<?php


namespace App\Support\Vendors\Import\Apacer;


use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorkSheetsMapping
{
    /**
     * @param Spreadsheet $spreadsheet
     * @return Worksheet
     * @throws Exception
     */
    public function getPriceWorksheet(Spreadsheet $spreadsheet)
    {
        return $spreadsheet->getSheet(0);
    }

    /**
     * Get start row number of price sheet.
     *
     * @return int
     */
    public function getPriceWorksheetStartRow(): int
    {
        return 9;
    }
}
