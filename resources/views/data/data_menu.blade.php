<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            データ管理Menu
        </h2>
        <div class="md:flex-auto p-1 text-gray-900 dark:text-gray-100  ">
            <div class="md:flex py-4 ml-4 md:w-2/3">
            <div class="flex">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('menu') }}'" >Menu</button>
            <button type="button" class="w-40 h-8 ml-2 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('data.data_index') }}'" >DataMenu</button>
            </div>
            <div class="flex md:mt-0 mt-2">
            <button type="button" class="w-40 h-8 md:ml-2 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('data.create') }}'" >反映・更新</button>
            <button type="button" class="w-40 h-8 ml-2 text-center text-sm text-white bg-red-500 border-0 py-1 px-2 focus:outline-none hover:bg-red-700 rounded " onclick="location.href='{{ route('data.delete_index') }}'" >削除Menu</button>
            </div>
            </div>


        </div>

    </x-slot>

</x-app-layout>
