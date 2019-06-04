<?php

namespace App\Http\Controllers\Shop;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductCommentController extends Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Product
     */
    private $product;

    /**
     * ProductCommentController constructor.
     * @param Request $request
     * @param Product $product
     */
    public function __construct(Request $request, Product $product)
    {
        $this->request = $request;
        $this->product = $product;
    }


    /**
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store()
    {
        $this->validate($this->request, [
            'products_id' => 'integer',
            'name' => 'string|max:32',
            'rating' => 'integer|min:0|max:5',
            'comment' => 'string|max:512',
        ]);

        $product = $this->product->newQuery()->findOrFail($this->request->get('products_id'));

        $commentData = $this->request->only(['rating', 'comment']);

        if (auth('web')->check()){
            $commentData['users_id'] = auth('web')->id();
        }else{
            $commentData['name'] = Str::ucfirst($this->request->get('name'));
        }

        $product->productComments()->create($commentData);

        $product->touch();

        // update product rating
        if ($this->request->get('rating')){
            $product->rating = ($product->rating * $product->rating_quantity + $this->request->get('rating')) / ($product->rating_quantity + 1);
            $product->rating_quantity = $product->rating_quantity + 1;
            $product->save();
        }

        return back();
    }
}
