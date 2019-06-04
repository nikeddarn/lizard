<?php


namespace App\Support\Vendors\Import\Apacer;


use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class SpecificationResolver
{
    /**
     * @var PriceListMapping
     */
    private $priceListMapping;
    /**
     * @var SpecificationMapping
     */
    private $specificationMapping;

    /**
     * ProductDataCreator constructor.
     * @param PriceListMapping $priceListMapping
     * @param SpecificationMapping $specificationMapping
     */
    public function __construct(PriceListMapping $priceListMapping, SpecificationMapping $specificationMapping)
    {
        $this->priceListMapping = $priceListMapping;
        $this->specificationMapping = $specificationMapping;
    }

    /**
     * Get specification row that match price list row.
     *
     * @param Spreadsheet $specificationSpreadsheet
     * @param Row $priceListRow
     * @return Row|null
     * @throws Exception
     */
    public function getSpecificationRow(Spreadsheet $specificationSpreadsheet, Row $priceListRow)
    {
        foreach ($specificationSpreadsheet->getAllSheets() as $index => $sheet){
            $sheetIndex = $index + 1;

            $sheetStartRow = $this->specificationMapping->getSpecificationWorksheetStartRow($sheetIndex);

            foreach ($sheet->getRowIterator($sheetStartRow) as $specificationRow){
                if ($this->matchRows($sheetIndex, $priceListRow, $specificationRow)){
                    // add sheet index in row for resolve column name
                    $specificationRow->sheetIndex = $sheetIndex;
                    return $specificationRow;
                }
            }
        }

        return null;
    }

    /**
     * Is rows match?
     *
     * @param int $sheetIndex
     * @param Row $priceListRow
     * @param Row $specificationRow
     * @return bool
     * @throws Exception
     */
    private function matchRows(int $sheetIndex, Row $priceListRow, Row $specificationRow): bool
    {
        $priceListModel = $this->priceListMapping->getModel($priceListRow);

        $specificationModel = $this->specificationMapping->getModel($sheetIndex, $specificationRow);

        if (!$specificationModel){
            return false;
        }

        $priceListModelParts = preg_split('/[\W]+/', $priceListModel);

        foreach ($priceListModelParts as $priceListModelPart){
            if (stripos($specificationModel, $priceListModelPart) === false){
                return false;
            }
        }

        return true;
    }
}
