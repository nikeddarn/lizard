<?php

namespace App\Http\Controllers\Import;

use App\Contracts\Vendor\SyncTypeInterface;
use App\Http\Requests\Import\ApacerImportRequest;
use App\Http\Controllers\Controller;
use App\Jobs\Apacer\InsertProduct;
use App\Support\Vendors\Import\Apacer\PriceListMapping;
use App\Support\Vendors\Import\Apacer\SpecificationMapping;
use App\Support\Vendors\Import\Apacer\SpecificationResolver;
use App\Support\Vendors\Import\Apacer\WorkSheetsMapping;
use Exception;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use stdClass;

class ApacerImportController extends Controller
{
    /**
     * @var WorkSheetsMapping
     */
    private $sheetsMapping;
    /**
     * @var SpecificationResolver
     */
    private $specificationResolver;
    /**
     * @var PriceListMapping
     */
    private $priceListMapping;
    /**
     * @var SpecificationMapping
     */
    private $specificationMapping;

    /**
     * ApacerImportController constructor.
     * @param WorkSheetsMapping $sheetsMapping
     * @param SpecificationResolver $specificationResolver
     * @param PriceListMapping $priceListMapping
     * @param SpecificationMapping $specificationMapping
     */
    public function __construct(WorkSheetsMapping $sheetsMapping, SpecificationResolver $specificationResolver, PriceListMapping $priceListMapping, SpecificationMapping $specificationMapping)
    {
        $this->sheetsMapping = $sheetsMapping;
        $this->specificationResolver = $specificationResolver;
        $this->priceListMapping = $priceListMapping;
        $this->specificationMapping = $specificationMapping;
    }

    /**
     * Show form for upload source file.
     *
     * @return View
     */
    public function index()
    {
        return view('content.admin.import.apacer.index');
    }

    public function import(ApacerImportRequest $request)
    {
        try {
            $priceListFileName = $request->file('price_list')->getPathname();
            $reader = IOFactory::createReaderForFile($priceListFileName);
            $reader->setReadDataOnly(true);

            $priceListSpreadsheet = $reader->load($priceListFileName);

            if ($request->has('specification')) {
                $specificationFileName = $request->file('specification')->getPathname();
                $specificationReader = IOFactory::createReaderForFile($specificationFileName);
                $specificationReader->setReadDataOnly(true);

                $specificationSpreadsheet = $specificationReader->load($specificationFileName);
            } else {
                $specificationSpreadsheet = null;
            }

            $priceListSheet = $this->sheetsMapping->getPriceWorksheet($priceListSpreadsheet);
            $priceListSheetStartRow = $this->sheetsMapping->getPriceWorksheetStartRow();

            foreach ($priceListSheet->getRowIterator($priceListSheetStartRow) as $priceListRow) {

                if ($specificationSpreadsheet) {
                    $specificationRow = $this->specificationResolver->getSpecificationRow($specificationSpreadsheet, $priceListRow);
                } else {
                    $specificationRow = null;
                }

                $productRawData = $this->getRawProductData($priceListRow, $specificationRow);

                if ($productRawData->price) {
                    $this->dispatch(
                        (new InsertProduct($productRawData))
                            ->onQueue(SyncTypeInterface::INSERT_PRODUCT)
                            ->onConnection('database')
                    );
                }
            }
        } catch (Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }

        return back()->with([
            'successful' => true,
        ]);
    }

    /**
     * @param Row $priceListRow
     * @param Row $specificationRow
     * @return stdClass
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function getRawProductData(Row $priceListRow, Row $specificationRow = null)
    {
        $data = new stdClass();

        $data->solution = $this->priceListMapping->getSolution($priceListRow);
        $data->formFactor = $this->priceListMapping->getFormFactor($priceListRow);
        $data->model = $this->priceListMapping->getModel($priceListRow);
        $data->specification = $this->priceListMapping->getSpecification($priceListRow);
        $data->temperature = $this->priceListMapping->getTemperature($priceListRow);
        $data->capacity = $this->priceListMapping->getCapacity($priceListRow);
        $data->chipType = $this->priceListMapping->getChipType($priceListRow);
        $data->modelNumber = $this->priceListMapping->getModelNumber($priceListRow);
        $data->price = $this->priceListMapping->getIncomingPrice($priceListRow);

        if ($specificationRow) {
            $data->introduction = $this->specificationMapping->getIntroduction($specificationRow->sheetIndex, $specificationRow);
            $data->imageUrl = $this->specificationMapping->getImageUrl($specificationRow->sheetIndex, $specificationRow);
            $data->specificationUrl = $this->specificationMapping->getSpecificationUrl($specificationRow->sheetIndex, $specificationRow);
        }

        return $data;
    }
}
