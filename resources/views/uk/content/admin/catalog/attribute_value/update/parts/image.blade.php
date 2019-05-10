@if($attributeValue->image)
    <img id="attribute-value-image" src="/storage/{{ $attributeValue->image }}"
         class="card-list-image">
@else
    <img id="attribute-value-image" src="" class="card-list-image d-none">
@endif