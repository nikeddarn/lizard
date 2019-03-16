<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Support\ImageHandlers\BrandImageHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BrandController extends Controller
{
    /**
     * @var Brand
     */
    private $brand;

    /**
     * BrandController constructor.
     * @param Brand $brand
     */
    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('local-catalog-show', auth('web')->user())) {
            abort(401);
        }

        return view('content.admin.catalog.brand.list.index')->with([
            'brands' => $this->brand->newQuery()->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        return view('content.admin.catalog.brand.create.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param BrandImageHandler $imageHandler
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, BrandImageHandler $imageHandler)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $this->validate($request, [
            'name' => 'required|string|max:32',
            'image' => 'image',
        ]);

        $brand = $this->brand->newQuery()->create($request->only('name'));

        if ($request->has('image')) {
            // uploaded image path
            $sourceImagePath = $request->image;
            // stored image path
            $destinationImagePath = 'images/brands/' . $brand->id . '/' . uniqid() . '.jpg';
            // create image
            $imageHandler->createBrandImage($sourceImagePath, $destinationImagePath);

            // save image
            $brand->image = $destinationImagePath;
            $brand->save();
        }

        return redirect(route('admin.brands.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        return view('content.admin.catalog.brand.update.index')->with([
            'brand' => $this->brand->newQuery()->findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $id
     * @param BrandImageHandler $imageHandler
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, string $id, BrandImageHandler $imageHandler)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $brand = $this->brand->newQuery()->findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:32',
            'image' => 'nullable|image',
        ]);

        $brandData = $request->only('name');

        if ($request->has('image')) {
            // delete previous image
            $imageHandler->deleteBrandImage($brand->id);

            // uploaded image path
            $sourceImagePath = $request->image;
            // stored image path
            $destinationImagePath = 'images/brands/' . $brand->id . '/' . uniqid() . '.jpg';
            // create image
            $imageHandler->createBrandImage($sourceImagePath, $destinationImagePath);

            // save image
            $brandData['image'] = $destinationImagePath;
        }

        $brand->update($brandData);

        return redirect(route('admin.brands.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @param BrandImageHandler $imageHandler
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(string $id, BrandImageHandler $imageHandler)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $brand = $this->brand->newQuery()->findOrFail($id);

        // delete previous image
        $imageHandler->deleteBrandImage($brand->id);

        $brand->delete();

        return redirect(route('admin.brands.index'));
    }
}
