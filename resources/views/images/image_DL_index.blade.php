<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2 mt-4">
            画像リスト 　<br>
        </h2>
        <div class="md:flex  md:ml-4 mb-2">

            <div class="ml-4 flex mt-2 md:mt-0">
                <div class="ml-0 md:ml-4">
                    <button onclick="location.href='{{ route('hinban_image_index') }}'" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded">画像リスト</button>
                </div>

            </div>
        </div>
        <br>
        <h3>
            <div class="flex">
            <div class="ml-2 text-ml text-green-600">
                選択した画像をまとめてダウンロード
            </div>

            </div>
        </h3>

        <form method="POST" action="{{ route('images_download') }}">
            @csrf

            <button type="submit" class="ml-40 w-40 h-8 text-center text-sm text-white bg-blue-500 border-0 py-1 px-2 focus:outline-none hover:bg-blue-700 rounded " >ダウンロード</button>
            <div style="display: flex; flex-wrap: wrap;">
                @foreach ($images as $image)
                    <div style="margin: 10px;">
                        <label>
                            <input type="checkbox" name="images[]" value="{{ $image }}"> {{ $image }}
                            <img src="{{ asset('storage/sku_images/' . $image) }}" style="width: 100px;"><br>

                        </label>
                    </div>
                @endforeach
            </div>

        </form>

</x-app-layout>
