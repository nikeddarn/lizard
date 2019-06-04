<?php


namespace App\Support\Vendors\Import\Apacer;


use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class PriceListMapping
{
    /**
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getSolution(Row $row): string
    {
        $rowIndex = $row->getRowIndex();
        $cell = $row->getWorksheet()->getCell('A' . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getFormFactor(Row $row): string
    {
        $rowIndex = $row->getRowIndex();
        $cell = $row->getWorksheet()->getCell('B' . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getModel(Row $row): string
    {
        $rowIndex = $row->getRowIndex();
        $cell = $row->getWorksheet()->getCell('C' . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getSpecification(Row $row): string
    {
        $rowIndex = $row->getRowIndex();
        $cell = $row->getWorksheet()->getCell('D' . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getTemperature(Row $row): string
    {
        $rowIndex = $row->getRowIndex();
        $cell = $row->getWorksheet()->getCell('E' . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getCapacity(Row $row): string
    {
        $rowIndex = $row->getRowIndex();
        $cell = $row->getWorksheet()->getCell('F' . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getChipType(Row $row): string
    {
        $rowIndex = $row->getRowIndex();
        $cell = $row->getWorksheet()->getCell('G' . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getModelNumber(Row $row): string
    {
        $rowIndex = $row->getRowIndex();
        $cell = $row->getWorksheet()->getCell('H' . $rowIndex);

        return $cell ? $cell->getValue() : null;
    }

    /**
     * @param Row $row
     * @return string
     * @throws Exception
     */
    public function getIncomingPrice(Row $row): string
    {
        $rowIndex = $row->getRowIndex();
        $cell = $row->getWorksheet()->getCell('J' . $rowIndex);

        $value = $cell ? floatval($cell->getValue()) : null;

        return is_float($value) ? $value : null;
    }
}
