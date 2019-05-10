@foreach($storages as $storage)

    <div class="card card-body storage-card my-4" data-lat="{{ $storage->latitude }}"
         data-lon="{{ $storage->longitude }}">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-4 mb-2 mb-md-0">
                <h2 class="h5 text-gray-hover bold mb-5">
                    <i class="svg-icon-larger" data-feather="map-pin"></i>
                    <span>{{ $storage->address_uk }}</span>
                </h2>

                <div class="pl-4 mb-4">
                    @foreach($storage->storagePhones as $phone)
                        <div class="mb-3 text-gray-hover">
                            <i class="svg-icon" data-feather="phone"></i>
                            <strong class="ml-2">{{ $phone->phone }}</strong>
                        </div>
                    @endforeach
                </div>

                <div class="pl-4">
                    @foreach($storage->workDays as $workDay)
                        <div class="mb-3 text-gray-hover">
                            <span>
                                <i class="svg-icon" data-feather="clock"></i>
                                <strong class="ml-2">{{ $workDay->name_uk }}</strong>
                            </span>
                            <span class="ml-3">
                                <strong>{{ $workDay->pivot->start_time }} - {{ $workDay->pivot->end_time }}</strong>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-8">
                <div class="contact-map" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>

@endforeach
