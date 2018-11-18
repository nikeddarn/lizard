<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->brand);

        $this->validate($request, [
            'name' => 'required|string|max:32',
            'image' => 'nullable|image',
        ]);

        $brandData = [
            'name' => $request->get('name'),
        ];

        if ($request->has('image')) {
            $brandData['image'] = 'images/brands/' . uniqid() . '.jpg';
            $categoryImageSizes = config('shop.images.brand');
            $createdImage = Image::make($request->image)->resize($categoryImageSizes['w'], $categoryImageSizes['h']);
            Storage::disk('public')->put($brandData['image'], $createdImage->stream('jpg', 100));
        }

        $this->brand->newQuery()->create($brandData);

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
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update', $this->brand);

        $this->validate($request, [
            'name' => 'required|string|max:32',
            'image' => 'nullable|image',
        ]);

        $brandData = [
            'name' => $request->get('name'),
        ];

        if ($request->has('image')) {
            $brandData['image'] = 'images/brands/' . uniqid() . '.jpg';
            $categoryImageSizes = config('shop.images.brand');
            $createdImage = Image::make($request->image)->resize($categoryImageSizes['w'], $categoryImageSizes['h']);
            Storage::disk('public')->put($brandData['image'], $createdImage->stream('jpg', 100));
        }

        $this->brand->newQuery()->findOrFail($id)->update($brandData);

        return redirect(route('admin.brands.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $this->brand);

        $this->brand->newQuery()->findOrFail($id)->delete();

        return redirect(route('admin.brands.index'));
    }
}
