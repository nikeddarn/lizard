<?php


namespace App\Support\Vendors\Import\Apacer;


use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class SpecificationMapping
{
    /**
     * Get start row number of price sheet.
     *
     * @param int $sheetIndex
     * @return int
     */
    public function getSpecificationWorksheetStartRow(int $sheetIndex): int
    {
        switch ($sheetIndex) {
            case 1:
                return 9;
            case 2:
                return 2;
            case 3:
                return 2;
            case 4:
                return 2;
            case 5:
                return 2;
            case 6:
                return 2;
            default:
                return 1;
        }
    }

    /**
     * @param int $sheetIndex
     * @param Row $row
     * @return string|null
     * @throws Exception
     */
    public function getModel(int $sheetIndex, Row $row)
    {
        switch ($sheetIndex) {
            case 1:
                $cellName =  'C';
                break;
            case 2:
                $cellName =  'C';
                break;
            case 3:
                $cellName =  'C';
                break;
            case 4:
                $cellName =  'C';
                break;
            case 5:
                $cellName =  'C';
                break;
            case 6:
                $cellName =  'C';
                break;
            default:
                throw new Exception('Mismatch sheets count in specification file');
        }

        $rowIndex = $row->getRowIndex();

        $cell = $row->getWorksheet()->getCell($cellName . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * @param int $sheetIndex
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getIntroduction(int $sheetIndex, Row $row): string
    {
        switch ($sheetIndex) {
            case 1:
                $cellName =  'J';
                break;
            case 2:
                $cellName =  'J';
                break;
            case 3:
                $cellName =  'J';
                break;
            case 4:
                $cellName =  'J';
                break;
            case 5:
                $cellName =  'J';
                break;
            case 6:
                $cellName =  'K';
                break;
            default:
                throw new Exception('Mismatch sheets count in specification file');
        }

        $rowIndex = $row->getRowIndex();

        $cell = $row->getWorksheet()->getCell($cellName . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * @param int $sheetIndex
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getImageUrl(int $sheetIndex, Row $row): string
    {
        switch ($sheetIndex) {
            case 1:
                $cellName =  'I';
                break;
            case 2:
                $cellName =  'I';
                break;
            case 3:
                $cellName =  'I';
                break;
            case 4:
                $cellName =  'I';
                break;
            case 5:
                $cellName =  'I';
                break;
            case 6:
                $cellName =  'J';
                break;
            default:
                throw new Exception('Mismatch sheets count in specification file');
        }

        $rowIndex = $row->getRowIndex();

        $cell = $row->getWorksheet()->getCell($cellName . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * @param int $sheetIndex
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getSpecificationUrl(int $sheetIndex, Row $row): string
    {
        switch ($sheetIndex) {
            case 1:
                $cellName =  'H';
                break;
            case 2:
                $cellName =  'H';
                break;
            case 3:
                $cellName =  'H';
                break;
            case 4:
                $cellName =  'H';
                break;
            case 5:
                $cellName =  'H';
                break;
            case 6:
                $cellName =  'I';
                break;
            default:
                throw new Exception('Mismatch sheets count in specification file');
        }

        $rowIndex = $row->getRowIndex();

        $cell = $row->getWorksheet()->getCell($cellName . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }
}
