<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Menu
        </h2>

        <div class="flex px-5 py-2 md:w-2/3">
            <button type="button" class="w-40 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-indigo-500  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('data.data_menu') }}'" >Data管理</button>
            {{-- <button type="button" class="w-32 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-green-500  hover:bg-green-600 rounded" onclick="location.href='{{ route('data.create') }}'" >Data反映</button>
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-red-400  hover:bg-red-600 rounded" onclick="location.href='{{ route('data.delete_index') }}'" >削除Menu</button> --}}
        </div>


        <div class="md:flex-auto p-1 text-gray-900  mt-8">
            <div class="flex px-4 py-2 md:w-2/3">
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('data.user_index') }}'" >メンバーデータ</button>
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('data.vendor_index') }}'" >仕入先データ</button>
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('data.unit_index') }}'" >UNITデータ</button>
            </div>
            <div class="flex px-4 py-2 md:w-2/3">
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('data.face_index') }}'" >Faceデータ</button>
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('data.brand_index') }}'" >BRANDデータ</button>
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('data.hinban_index') }}'" >品番データ</button>
            </div>
            <div class="flex px-4 py-2 md:w-2/3">
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('data.sku_index') }}'" >SKUデータ</button>
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('data.col_index') }}'" >Colデータ</button>
            <button type="button" class="w-32 h-8 ml-4 border-gray-900 p-0 text-sm text-white bg-indigo-400  hover:bg-indigo-600 rounded" onclick="location.href='{{ route('data.size_index') }}'" >Sizeデータ</button>
            </div>




        </div>

    </x-slot>

</x-app-layout>
