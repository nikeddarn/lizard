<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductVideo\StoreProductVideoRequest;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\ProductVideo;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductVideoController extends Controller
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var ProductVideo
     */
    private $productVideo;

    /**
     * ProductImageController constructor.
     * @param Product $product
     * @param ProductVideo $productVideo
     */
    public function __construct(Product $product, ProductVideo $productVideo)
    {
        $this->product = $product;
        $this->productVideo = $productVideo;
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

        return view('content.admin.catalog.product_video.create.index')->with(compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreProductVideoRequest $request
     * @return RedirectResponse
     * @throws FileNotFoundException
     */
    public function store(StoreProductVideoRequest $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $product = $this->product->newQuery()->findOrFail($request->get('products_id'));

        if ($request->get('video_youtube')) {
            $productVideoData = [
                'youtube' => $request->get('video_youtube'),
            ];

            $product->productVideos()->create($productVideoData);
        } elseif ($request->has('video_mp4') || $request->has('video_webm')) {
            $productVideoData = [];
            $productVideoFolder = 'video/products/' . $product->id . '/';

            if ($request->has('video_mp4')) {
                $destinationPath = $productVideoFolder . uniqid() . '.' . $request->file('video_mp4')->extension();
                $productVideoData['mp4'] = $destinationPath;
                $video = $request->file('video_mp4')->get();
                Storage::disk('public')->put($destinationPath, $video);
            }

            if ($request->has('video_webm')) {
                $destinationPath = $productVideoFolder . uniqid() . '.' . $request->file('video_webm')->extension();
                $productVideoData['webm'] = $destinationPath;
                $video = $request->file('video_webm')->get();
                Storage::disk('public')->put($destinationPath, $video);
            }

            $product->productVideos()->create($productVideoData);
        }

        return redirect(route('admin.products.show', ['id' => $product->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $video = $this->productVideo->newQuery()->findOrFail($id);

        $productId = $video->products_id;

        // remove video mp4 from storage
        if ($video->mp4) {
            Storage::disk('public')->delete($video->mp4);
        }
        // remove video mp4 from storage
        if ($video->webm) {
            Storage::disk('public')->delete($video->webm);
        }

        // delete image
        $video->delete();

        return redirect(route('admin.products.show', ['id' => $productId]));

    }
}
