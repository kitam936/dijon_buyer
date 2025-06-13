<x-app-layout>
    <x-slot name="header">

            <h2 class="flex font-semibold text-xl text-gray-800 leading-tight">
            <div>
                各種データ登録
            </div>
            </h2>

            <div class="flex ml-8 text-sm items-right mb-0 mt-4">
                <button type="button" class="w-40 ml-2 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('data.data_menu') }}'" >データ管理</button>
                <button onclick="location.href='{{ route('data.create') }}'" class="ml-8 h-8 text-black bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-300 rounded text-ml">クリア</button>

            </div>


    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">

            <x-flash-message status="session('status')" />
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}


                    <div class="-m-2">

                        <div class="p-2">

                        <form method="POST" action="{{ route('data.sku_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="sku_data" class="leading-7 text-sm text-gray-600">SKUデータ　</label>
                            <input type="file" id="sku_data" name="sku_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-sm text-white bg-green-500 border-0 py-1 px-4 focus:outline-none hover:bg-green-600 rounded">SKUデータ更新</button>
                        </form>

                        <form method="POST" action="{{ route('data.hinban_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="hinban_data" class="leading-7 text-sm text-gray-600">品番データ　</label>
                            <input type="file" id="hinban_data" name="hinban_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-sm text-white bg-green-500 border-0 py-1 px-4 focus:outline-none hover:bg-green-600 rounded">品番データ更新</button>
                        </form>

                        {{-- <form method="POST" action="{{ route('data.predata_upload') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="predata" class="leading-7 text-sm text-gray-600">事前品番データ</label>
                            <input type="file" id="predata" name="predata" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-sm text-white bg-green-500 border-0 py-1 px-4 focus:outline-none hover:bg-green-600 rounded">事前品番データ更新</button>
                        </form> --}}

                        <form method="POST" action="{{ route('data.vendor_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="co_data" class="leading-7 text-sm text-gray-600">仕入先データ</label>
                            <input type="file" id="co_data" name="co_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-sm text-white bg-green-500 border-0 py-1 px-4 focus:outline-none hover:bg-green-600 rounded">仕入先データ更新</button>
                        </form>

                        <form method="POST" action="{{ route('data.unit_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="unit_data" class="leading-7 text-sm text-gray-600">UNITデータ　</label>
                            <input type="file" id="unit_data" name="unit_data" accept=“.csv” class="w-1/3 ml-2 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-sm text-white bg-green-500 border-0 py-1 px-4 focus:outline-none hover:bg-green-600 rounded">UNITデータ更新</button>
                        </form>
                        <form method="POST" action="{{ route('data.face_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="face_data" class="leading-7 text-sm text-gray-600">Faceデータ　</label>
                            <input type="file" id="face_data" name="face_data" accept=“.csv” class="w-1/3 ml-2 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-sm text-white bg-green-500 border-0 py-1 px-4 focus:outline-none hover:bg-green-600 rounded">Faceデータ更新</button>
                        </form>
                        <form method="POST" action="{{ route('data.brand_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="brand_data" class="leading-7 text-sm text-gray-600">Brandデータ　</label>
                            <input type="file" id="brand_data" name="brand_data" accept=“.csv” class="w-1/3 ml-1 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-sm text-white bg-green-500 border-0 py-1 px-4 focus:outline-none hover:bg-green-600 rounded">Brandデータ更新</button>
                        </form>

                        <form method="POST" action="{{ route('data.col_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="col_data" class="leading-7 text-sm text-gray-600 mr-2">Colデータ　</label>
                            <input type="file" id="col_data" name="col_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-sm text-white bg-green-500 border-0 py-1 px-4 focus:outline-none hover:bg-green-600 rounded">Colデータ更新</button>
                        </form>

                        <form method="POST" action="{{ route('data.size_upsert') }}" class=" p-1" enctype="multipart/form-data">
                            @csrf
                            <label for="size_data" class="leading-7 text-sm text-gray-600 mr-1">Sizeデータ　</label>
                            <input type="file" id="size_data" name="size_data" accept=“.csv” class="w-1/3 ml-3 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <button type="submit" class="w-36 text-sm text-white bg-green-500 border-0 py-1 px-4 focus:outline-none hover:bg-green-600 rounded">Sizeデータ更新</button>
                        </form>





                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

