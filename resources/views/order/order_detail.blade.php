<x-app-layout>
    <x-slot name="header">

        <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-200 leading-tight">
        <div>
            発注詳細
        </div>
        </h2>

        <x-flash-message status="session('status')"/>
        <div class="md:flex ml-8 ">
        <div class="flex">
        <div class="ml-2 mb-2 md:mb-0">
            <button type="button" onclick="location.href='{{ route('analysis_index') }}'" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">Menu</button>
        </div>
        <div class="ml-2 mt-0 md:ml-2 md:mt-0">
            <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('order_index') }}'" >発注リスト</button>
        </div>
        </div>

        @if($user->shop_id < 1000)
        <div class="flex">
        <div class="ml-2 mb-2 md:mb-0">
            <form method="get" action="{{ route('order_csv') }}">
                <input type="hidden" name="id2" value="{{ $order_hs->id }}">
                <button type="submit" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded">
                    CSVダウンロード
                </button>
            </form>
        </div>


        <div class="ml-2 mb-0 md:mb-0">
            <button type="button" onclick="location.href='{{ route('order_edit',['order'=>$order_hs->id])}}'" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-600 rounded ">編集</button>
        </div>

        </div>

        {{-- <div>
        <form id="delete_{{$order_hs->id}}" method="POST" action="{{ route('order_destroy',['order' => $order_hs->id]) }}">
            @csrf
            @method('delete')
            <div class="md:px-4 py-0">
                <div class="p-0 w-full flex ml-2 mt-0 md:mt-0">
                <a href="#" data-id="{{ $order_hs->id }}" onclick="deletePost(this)" class="w-32 text-center text-sm text-white bg-red-500 border-0 py-1 px-2 focus:outline-none hover:bg-red-700 rounded  ">削除</a>
                </div>
            </div>
        </form>
        </div> --}}
        @endif


        </div>

    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 bg-white border-b border-gray-200">

                    {{-- <form method="get" action=""  enctype="multipart/form-data"> --}}

                        <div class="-m-2">
                            <div class="p-2 mx-auto">
                                {{-- @foreach ($order_hss as $order_hs) --}}

                                <div class="p-2 w-full mx-auto">
                                    <div class="md:flex">
                                    <div class="flex">
                                    <div class="relative">
                                        <label for="order_id" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">ID</label>
                                        <div  id="order_id" name="order_id" value="{{$order_hs->id}}" class="h-8 w-20 text-sm bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_hs->id}}
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <label for="date" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">日付</label>
                                        <div  id="date" name="date" value="{{$order_hs->order_date}}" class="ml-2 h-8 w-32 text-sm bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_hs->order_date}}
                                        </div>
                                    </div>
                                    </div>
                                    <div class="flex">
                                    <div class="relative">
                                        <label for="user_name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">投稿者</label>
                                        <div  id="user_name" name="user_name" value="{{$order_hs->name}}" class="md:ml-2 h-8 text-sm w-32 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_hs->name}}
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <label for="sh_name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">店名</label>
                                        <div  id="sh_name" name="sh_name" value="{{$order_hs->shop_name}}" class="ml-2 h-8 text-sm w-48 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_hs->shop_name}}
                                        </div>
                                    </div>
                                    </div>
                                    </div>
                                    <div class="relative">
                                        <label for="status" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Status</label>
                                        <div  id="status" name="status" value="{{$order_hs->status}}" class="h-8 text-sm w-32 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_hs->status}}
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <div class="relative">
                                            <label for="status" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">売価計</label>
                                            <div  id="total_baika" name="total_baika" value="{{$order_total->total_baika}}" class="h-8 text-sm w-36 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_total->total_baika}}円
                                            </div>
                                        </div>
                                        <div class="relative ml-2">
                                            <label for="status" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">原価計</label>
                                            <div  id="total_genka" name="total_genka" value="{{$order_total->total_genka}}" class="h-8 text-sm w-36 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_total->total_genka}}円
                                            </div>
                                        </div>
                                        <div class="relative ml-2">
                                            <label for="status" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">数計</label>
                                            <div  id="total_pcs" name="total_pcs" value="{{$order_total->total_pcs}}" class="h-8 text-sm w-36 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_total->total_pcs}}枚
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <label for="comment" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">comment</label>
                                        <div  id="comment" rows="5" name="comment" value="{{$order_hs->comment}}" class="h-8 text-sm w-full bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_hs->comment}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <input  type="hidden" id="order_id2" name="order_id2" value="{{$order_hs->id}}" class="h-8 w-20 text-sm bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out"> --}}
                        </input>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>


    <div class="py-0 border">
        <h2>明細</h2>
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-full bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        {{-- <th class="w-1/12 md:1/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Id</th> --}}
                        {{-- <th class="w-3/12 md:1/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">SKU</th> --}}
                        <th class="w-3/12 md:2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品番</th>
                        <th class="w-3/12 md:2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品名</th>
                        <th class="w-2/12 md:5/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Col</th>
                        <th class="w-2/12 md:5/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Size</th>
                        <th class="w-2/12 md:5/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">pcs</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($order_fs as $order_f)
                    <tr>
                        {{-- <td type="hidden" class="w-1/12 md:1/12 text-sm md:px-4 py-1 text-center"> {{ $order_f->id }}</td> --}}
                        {{-- <td type="hidden" class="w-3/12 md:1/12 text-sm md:px-4 py-1 text-center"> {{$order_f->sku_id}} </td> --}}
                        <td class="w-3/12 md:2/12 text-sm md:px-4 py-1 text-center">{{ $order_f->hinban_id }}</td>
                        <td class="w-3/12 md:2/12 text-sm md:px-4 py-1 text-center">{{ Str::limit($order_f->hinban_name,15) }}</td>
                        <td class="w-2/12 md:2/12 text-sm md:px-4 py-1 text-center">{{ $order_f->col_id }}</td>
                        <td class="w-2/12 md:2/12 text-sm md:px-4 py-1 text-center">{{ $order_f->size_id }}</td>
                        <td class="w-2/12 md:2/12 text-sm md:px-4 py-1 text-center">{{ $order_f->pcs }}</td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{-- {{  $order_fs->appends([
                'co_id'=>\Request::get('co_id'),
                'area_id'=>\Request::get('area_id'),
                'sh_id'=>\Request::get('sh_id'),
                'info'=>\Request::get('info'),
            ])->links()}} --}}
        </div>
    </div>


    <script>
        function deletePost(e) {
        'use strict';
        if (confirm('本当に削除してもいいですか?')) {
        document.getElementById('delete_' + e.dataset.id).submit();
        }
        }
    </script>

</x-app-layout>
