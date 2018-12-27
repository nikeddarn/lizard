<?php
/**
 * Create vendor product comments data.
 */

namespace App\Support\Vendors\Adapters;

class BrainProductCommentsDataAdapter
{
    /**
     * Prepare vendor product stock data.
     *
     * @param array $productComments
     * @return array
     */
    public function prepareVendorProductCommentsData(array $productComments): array
    {
        $productCommentsData = [];

        foreach ($productComments as $comment) {
            $productCommentsData[] = [
                'name' => $comment->author,
                'comment' => $comment->body,
                'created_at' => $comment->add_time,
                'updated_at' => $comment->add_time,
            ];
        }

        return $productCommentsData;
    }
}
