<?php
/**
 * Types of synchronizations.
 */

namespace App\Contracts\Vendor;


interface SyncTypeInterface
{
    const INSERT_PRODUCT = 'insert_vendor_product';

    const UPDATE_PRODUCT = 'update_vendor_product';
}
