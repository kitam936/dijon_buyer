<div class="mt-2 w-20">
    @if(empty($filename))
        <img src="{{ asset('images/no_image.jpg') }}">
    @else
        <img src="{{ asset('storage/sku_images/'.$filename) }}">

    @endif

</div>
