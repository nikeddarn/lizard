<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductFile\StoreProductFileRequest;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\ProductFile;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductFileController extends Controller
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var ProductFile
     */
    private $productFile;

    /**
     * ProductImageController constructor.
     * @param Product $product
     * @param ProductFile $productFile
     */
    public function __construct(Product $product, ProductFile $productFile)
    {
        $this->product = $product;
        $this->productFile = $productFile;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $id
     * @return Response
     */
    public function create(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $product = $this->product->newQuery()->findOrFail($id);

        return view('content.admin.catalog.product_file.create.index')->with(compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreProductFileRequest $request
     * @return RedirectResponse
     * @throws FileNotFoundException
     */
    public function store(StoreProductFileRequest $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $product = $this->product->newQuery()->findOrFail($request->get('products_id'));

        $productFilesFolder = 'files/products/' . $product->id . '/';

        $locale = app()->getLocale();
        $fileName = Str::slug($request->get('product_file_name_' . $locale));

        $destinationPath = $productFilesFolder . $fileName . '.' . $request->file('product_file')->extension();

        $file = $request->file('product_file')->get();

        Storage::disk('public')->put($destinationPath, $file);

        $productFileData = [
            'name_ru' => $request->get('product_file_name_ru'),
            'name_uk' => $request->get('product_file_name_uk'),
            'url' => $destinationPath,
        ];

        $product->productFiles()->create($productFileData);

        return redirect(route('admin.products.show', ['id' => $product->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $fail = $this->productFile->newQuery()->findOrFail($id);

        $productId = $fail->products_id;

        // remove file from storage
        Storage::disk('public')->delete($fail->url);

        // delete image
        $fail->delete();

        return redirect(route('admin.products.show', ['id' => $productId]));

    }
}
