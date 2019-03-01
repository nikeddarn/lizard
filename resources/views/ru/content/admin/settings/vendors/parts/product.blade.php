<div class="card p-5 mb-5">
    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" id="download-archive-product" name="download_archive_product"
               class="custom-control-input multi-inputs-checkbox"{{ $vendorsData['insert_product']['download_archive_product'] ? ' checked' : '' }}>
        <label class="custom-control-label" for="download-archive-product">Загружать архивные продукты</label>
    </div>
</div>

<div class="card p-5 mb-5">

    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" id="delete_product_on_delete_vendor_category" name="delete_product_on_delete_vendor_category"
               class="custom-control-input multi-inputs-checkbox"{{ $vendorsData['delete_product']['delete_product_on_delete_vendor_category'] ? ' checked' : '' }}>
        <label class="custom-control-label" for="delete_product_on_delete_vendor_category">Удалять продукты при удалении категории поставщика</label>
    </div>

    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" id="keep_link_in_stock_present_product_on_delete" name="keep_link_in_stock_present_product_on_delete_vendor_category"
               class="custom-control-input multi-inputs-checkbox"{{ $vendorsData['delete_product']['keep_link_in_stock_present_product_on_delete'] ? ' checked' : '' }}>
        <label class="custom-control-label" for="keep_link_in_stock_present_product_on_delete">Сохранять синхронизацию продуктов присутствующих на складе при удалении продукта поставщика</label>
    </div>

    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" id="delete_empty_local_category_on_delete_vendor_category" name="delete_empty_local_category_on_delete_vendor_category"
               class="custom-control-input multi-inputs-checkbox"{{ $vendorsData['delete_product']['delete_empty_local_category_on_delete_vendor_category'] ? ' checked' : '' }}>
        <label class="custom-control-label" for="delete_empty_local_category_on_delete_vendor_category">Удалять пустую локальную категорию при удалении категории поставщика</label>
    </div>

    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" id="delete_product_on_archive_vendor_product" name="delete_product_on_archive_vendor_product"
               class="custom-control-input multi-inputs-checkbox"{{ $vendorsData['delete_product']['delete_product_on_archive_vendor_product'] ? ' checked' : '' }}>
        <label class="custom-control-label" for="delete_product_on_archive_vendor_product">Удалять (или архивировать) продукт при архивации продукта поставщика</label>
    </div>

</div>
