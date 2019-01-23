<?php
/**
 * Category breadcrumbs.
 */

namespace App\Support\Breadcrumbs;


use App\Models\Category;
use Illuminate\Http\Request;

class CategoryBreadcrumbs
{
    /**
     * @var Category
     */
    private $category;
    /**
     * @var Request
     */
    private $request;

    /**
     * CategoryBreadcrumbs constructor.
     * @param Request $request
     * @param Category $category
     */
    public function __construct(Request $request, Category $category)
    {
        $this->category = $category;
        $this->request = $request;
    }

    /**
     * Get breadcrumbs for category.
     *
     * @param int $categoryId
     * @return array
     */
    public function getCategoryBreadcrumbs(int $categoryId): array
    {
        return $this->createCategoryBreadcrumbs($categoryId);
    }

    /**
     * Create category breadcrumbs.
     *
     * @param int $categoryId
     * @return array
     */
    protected function createCategoryBreadcrumbs(int $categoryId): array
    {
        $routeLocale = $this->request->route()->parameter('locale');

        // get breadcrumbs' categories
        $breadcrumbsCategories = $this->category->newQuery()->ancestorsAndSelf($categoryId);

        foreach ($breadcrumbsCategories as $category) {
            if ($category->isLeaf()) {
                $routeName = 'shop.category.leaf.index';
            } else {
                $routeName = 'shop.category.index';
            }

            $category->href = route($routeName, [
                'url' => $category->url,
                'locale' => $routeLocale,
            ]);
        }

        return $breadcrumbsCategories->pluck('href', 'name')->toArray();
    }
}
