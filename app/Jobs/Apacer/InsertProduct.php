<?php

namespace App\Jobs\Apacer;

use App\Support\Vendors\Import\Apacer\ProductCreator;
use ErrorException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PhpOffice\PhpSpreadsheet\Exception;
use stdClass;

class InsertProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;

    /**
     * @var stdClass
     */
    private $productRawData;


    /**
     * Create a new job instance.
     *
     * @param stdClass $productRawData
     */
    public function __construct(stdClass $productRawData)
    {
        $this->productRawData = $productRawData;
    }

    /**
     * Execute the job.
     *
     * @param ProductCreator $productCreator
     * @return void
     * @throws ErrorException
     * @throws Exception
     */
    public function handle(ProductCreator $productCreator)
    {
        $productCreator->createProductFromRawData($this->productRawData);
    }
}
