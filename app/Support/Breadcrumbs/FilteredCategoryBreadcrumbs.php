<?php
/**
 * Created filtered category breadcrumbs.
 */

namespace App\Support\Breadcrumbs;


use Illuminate\Support\Collection;

class FilteredCategoryBreadcrumbs extends CategoryBreadcrumbs
{
    /**
     * Get breadcrumbs for category.
     *
     * @param int $categoryId
     * @param Collection $selectedAttributeValues
     * @return array
     */
    public function getFilteredCategoryBreadcrumbs(int $categoryId, Collection $selectedAttributeValues): array
    {
        $breadcrumbs =  $this->createCategoryBreadcrumbs($categoryId);

        if ($selectedAttributeValues->count() === 1) {
            // get single attribute value
            $selectedAttributeValuesItem = $selectedAttributeValues->first();

            // add breadcrumb of single filter
            $breadcrumbs[$selectedAttributeValuesItem->value] = null;
        }

        return $breadcrumbs;
    }
}
