<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Support\ImageHandlers\BrandImageHandler;
use Illuminate\Http\Request;

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', $this->brand);

        return view('content.admin.catalog.brand.list.index')->with([
            'brands' => $this->brand->newQuery()->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->brand);

        return view('content.admin.catalog.brand.create.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param BrandImageHandler $imageHandler
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, BrandImageHandler $imageHandler)
    {
        $this->authorize('create', $this->brand);

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(string $id)
    {
        $this->authorize('update', $this->brand);

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, string $id, BrandImageHandler $imageHandler)
    {
        $this->authorize('update', $this->brand);

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(string $id, BrandImageHandler $imageHandler)
    {
        $this->authorize('delete', $this->brand);

        $brand = $this->brand->newQuery()->findOrFail($id);

        // delete previous image
        $imageHandler->deleteBrandImage($brand->id);

        $brand->delete();

        return redirect(route('admin.brands.index'));
    }
}
