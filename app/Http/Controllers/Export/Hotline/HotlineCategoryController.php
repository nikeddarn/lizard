<?php

namespace App\Http\Controllers\Export\Hotline;

use App\Contracts\Dealer\DealerInterface;
use App\Http\Requests\Export\Hotline\HotlineCategoriesRequest;
use App\Http\Controllers\Controller;
use App\Models\DealerCategory;
use Exception;
use Illuminate\View\View;

class HotlineCategoryController extends Controller
{
    /**
     * @var DealerCategory
     */
    private $dealerCategory;

    /**
     * HotlineCategoryController constructor.
     * @param DealerCategory $dealerCategory
     */
    public function __construct(DealerCategory $dealerCategory)
    {
        $this->dealerCategory = $dealerCategory;
    }

    /**
     * Show form for upload Hotline categories file.
     *
     * @return View
     */
    public function create()
    {
        return view('content.admin.export.hotline.categories.index');
    }

    public function store(HotlineCategoriesRequest $request)
    {
        try {
            $file = fopen($request->file('categories')->getPathname(), 'r');

            $parents = [];

            while (!feof($file)) {
                // get and convert string
                $string = mb_convert_encoding(fgets($file), "utf-8", "windows-1251");
                // explode string
                $stringData = explode(';', $string);

                // get category data
                $categoryLevel = count($stringData) - 1;
                $categoryName = trim(end($stringData));

                if (!$categoryName){
                    continue;
                }

                // get parent category
                $parentCategory = $categoryLevel > 0 ? $parents[$categoryLevel - 1] : null;
                $parentCategoryId = $parentCategory ? $parentCategory->id : null;

                // get or create category
                $category = $this->getOrCreateCategory($categoryName, $categoryLevel, $parentCategoryId);

                // set parent category
                $parents[$categoryLevel] = $category;
            }

        } catch (Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }

        return back()->with([
            'successful' => true,
        ]);
    }

    /**
     * Get or create category.
     *
     * @param string $categoryName
     * @param int $categoryLevel
     * @param int|null $parentCategoryId
     * @return DealerCategory
     * @throws Exception
     */
    private function getOrCreateCategory(string $categoryName, int $categoryLevel, int $parentCategoryId = null): DealerCategory
    {
        $category = $this->getExistingCategory($categoryName, $categoryLevel, $parentCategoryId);

        if (!$category) {
            $category = DealerCategory::scoped(['dealers_id' => DealerInterface::HOTLINE])->create([
                'dealers_id' => DealerInterface::HOTLINE,
                'name_ru' => $categoryName,
                'name_uk' => $categoryName,
            ]);

            if ($parentCategoryId){
                $parentCategory = DealerCategory::scoped(['dealers_id' => DealerInterface::HOTLINE])->findOrFail($parentCategoryId);

                $category->appendToNode($parentCategory)->save();
            }
        }

        return $category;
    }

    /**
     * @param string $categoryName
     * @param int $categoryLevel
     * @param int|null $parentCategoryId
     * @return mixed
     */
    private function getExistingCategory(string $categoryName, int $categoryLevel, int $parentCategoryId = null)
    {
        $categoryQuery = DealerCategory::scoped(['dealers_id' => DealerInterface::HOTLINE])
            ->where(function ($query) use ($categoryName) {
                $query->where('name_ru', $categoryName)
                    ->orWhere('name_uk', $categoryName);
            })
            ->withDepth()
            ->having('depth', '=', $categoryLevel);

        if ($parentCategoryId) {
            $categoryQuery->where('parent_id', $parentCategoryId);
        } else {
            $categoryQuery->whereNull('parent_id');
        }

        return $categoryQuery->first();
    }
}
