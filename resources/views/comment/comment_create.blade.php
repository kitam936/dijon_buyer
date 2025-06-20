<x-app-layout>
    <x-slot name="header">
        <div >
            <h2 class="mb-4 font-semibold text-xl  text-gray-800 dark:text-gray-200 leading-tight">
            <div>
                新規コメント登録
            </div>
            </h2>
            <div class="flex">
            <div class="ml-10 mb-2">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('hinban.hinban_index2') }}'" >登録品番Data</button>
            </div>
            <div class="ml-4 mb-2">
                <button type="button" onclick="location.href='{{ route('hinban_show',['id'=>$hinban->id]) }}'" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">商品詳細</button>
            </div>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}
                    <form method="post" action="{{ route('comment_store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="-m-2">
                        <div class="flex mb-0">

                                {{-- <div class="mb-2 md:ml-2 "> --}}
                                    {{-- <x-label for="shop_name" value="Shop名" /> --}}
                                    {{-- <div class="pl-2 w-72 h-6 text-sm items-center bg-gray-100 border rounded" name="shop_name" value="{{ $report->shop_name }}">{{ $report->shop_name }}</div> --}}
                                    {{-- <div class="pl-2 w-72 h-6 text-sm items-center bg-gray-100 border rounded" name="shop_id" value="{{ $report->shop_id }}">{{ $report->shop_id }}</div> --}}

                                {{-- </div> --}}

                                <div class="flex pl-0 mt-0">
                                    <input type="" class="pl-0  ml-0 md:ml-2 w-40 h-8 items-center bg-gray-100 border rounded" name="hinban_id2"  value="{{ $hinban->id }}"/>
                                    <input type="hidden" class="pl-0  ml-0 md:ml-2 w-40 h-8 items-center bg-gray-100 border rounded" name="user_id2"  value="{{ $login_user->id }}"/>
                                </div>

                        </div>

                        <div class="p-2 mx-auto">
                            <div>
                                <x-label for="comment" value="コメント　※必須" />
                                <x-textarea rows="5" id="comment" class="block mt-1 w-full" type="text" name="comment" required>{{ old('comment')  }}</x-textarea>
                            </div>
                        </div>



                        <div class="p-2 w-1/2 mx-auto">

                        <div class="p-2 w-full mt-4 flex justify-around">

                            <button type="submit" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded ">登録</button>
                        </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

