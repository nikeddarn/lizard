@if ($errors->any())
    <div class="my-5">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger py-1" role="alert">
                <span class="ml-2">{{ $error }}</span>
            </div>
        @endforeach
    </div>
@endif
