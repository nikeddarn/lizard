<div class="card py-2 px-1 p-lg-5 mb-5">
    <h4 class="mb-5 text-center">Поставщики</h4>

    @if($product->vendors->count())

        <table class="table">

            <tbody>

            @foreach($product->vendors as $vendor)

                <tr>

                    <td><strong>{{ $vendor->name_ru }}</strong></td>

                </tr>


            @endforeach

            </tbody>

        </table>

    @endif

</div>
