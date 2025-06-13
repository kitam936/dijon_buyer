<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <div>
                データ登録結果
            </div>
            </h2>

        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex p-6 text-gray-900 dark:text-gray-100">
                    <div class="">
                       データを{{ $count }}件　登録しました。
                    </div>
                    <div class="pl-20">
                        <button onclick="location.href='{{ route('data.create') }}'" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">データ管理画面</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

