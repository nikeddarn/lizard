<?php

namespace App\Http\Controllers\Import;

use App\Http\Requests\Import\ImportFromExcelFileRequest;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ApacerImportController extends Controller
{
    /**
     * @var Xls
     */
    private $xlsReader;

    /**
     * ApacerImportController constructor.
     * @param Xls $xlsReader
     */
    public function __construct(Xls $xlsReader)
    {
        $this->xlsReader = $xlsReader;
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

    public function import(ImportFromExcelFileRequest $request)
    {
        try{
            $spreadsheet = $this->createSpreadsheet($request);
            var_dump($spreadsheet);exit;
        }catch (Exception $exception){
            return back()->withErrors([$exception->getMessage()]);
        }

        return back()->with([
            'successful' => true,
        ]);
    }

    /**
     * @param ImportFromExcelFileRequest $request
     * @return Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function createSpreadsheet(ImportFromExcelFileRequest $request)
    {
        $inputFileName = $request->file('source_file')->getPathname();

        return \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
    }
}
