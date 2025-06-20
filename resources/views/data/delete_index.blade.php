<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <h2 class="flex font-semibold text-xl text-gray-800 leading-tight">
                データ削除
            <div class="ml-24 w-40 text-sm items-right mb-0">
                <button onclick="location.href='{{ route('data.data_menu') }}'" class="w-40 h-8 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-ml">データ管理</button>
            </div>
            </h2>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">

            <x-flash-message status="session('status')" />
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}


                    <div class="-m-2">

                        <div class="p-2">


                            <div calss="flex">
                              </div>


                            <div class="mt-8">
                            <span class="items-center mt-12 mr-20" >データ全削除</span>
                            </div>



                        <div class="flex mt-2">

                            <form method="POST" action="{{ route('data.vendor_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">会社データ削除</button>
                            </form>

                            <form method="POST" action="{{ route('data.col_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Colデータ削除</button>
                            </form>

                            <form method="POST" action="{{ route('data.size_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Sizeデータ削除</button>
                            </form>
                        </div>

                        <div class="flex mt-2">
                            <form method="POST" action="{{ route('data.unit_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Unitデータ削除</button>
                            </form>

                            <form method="POST" action="{{ route('data.face_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Faceデータ削除</button>
                            </form>

                            <form method="POST" action="{{ route('data.brand_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">Brandデータ削除</button>
                            </form>
                        </div>

                        <div class="flex mt-2">

                            {{-- <form method="POST" action="{{ route('data.sku_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">SKUデータ削除</button>
                            </form> --}}

                            <form method="POST" action="{{ route('data.hinban_destroy') }}" class=" ml-0 p-1 items-right " >
                                @csrf
                                @method('delete')

                                <button type="submit" class="text-sm 0 w-32 text-white bg-red-500 border-0 py-1 px-4 focus:outline-none hover:bg-red-600 rounded">品番データ削除</button>
                            </form>

                        </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

