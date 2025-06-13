<x-app-layout>
    <x-slot name="header">

        <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-200 leading-tight">
        <div>
            発注編集
        </div>
        </h2>

        <x-flash-message status="session('status')"/>
        <div class="md:flex ml-8 ">
        <div class="flex">
        <div class="ml-2 mb-2 md:mb-0">
            <button type="button" onclick="location.href='{{ route('analysis_index') }}'" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">Menu</button>
        </div>
        <div class="ml-2 mt-0 md:ml-2 md:mt-0">
            <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('order_index') }}'" >追加発注リスト</button>
        </div>
        </div>
        <div class="flex">
        {{-- <div class="ml-2 mb-2 md:mb-0">
            <button type="button" onclick="location.href=''" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded ">ダウンロード</button>
        </div> --}}

        {{--  @foreach ($order_hss as $order_hs)  --}}
        @if($user->shop_id > 1000)

        {{-- <div class="ml-2 mb-0 md:mb-0">
            <button type="button" onclick="location.href='{{ route('order_edit',['order'=>$order_hs->id])}}'" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-600 rounded ">編集</button>
        </div> --}}
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
        {{-- @endif --}}

        </div>

    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 bg-white border-b border-gray-200">

                    <form method="post" action="{{ route('order_update',['order'=>$order_hs->id])}}"  >
                        @csrf
                        {{-- @method('put') --}}
                        <div class="-m-2">
                            <div class="p-2 mx-auto">
                                {{-- @foreach ($order_hss as $order_hs) --}}

                                <div class="p-2 w-full mx-auto">
                                    <div class="md:flex">
                                    <div class="flex">
                                    <div class="relative">
                                        <label for="order_id" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">ID</label>
                                        <div id="order_id" name="order_id" value="{{$order_hs->id}}" class="h-8 w-20 text-sm bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_hs->id}}
                                        </div>
                                    </div>
                                    <div class="relative mr-2">
                                        <label for="date" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">日付</label>
                                        <div  id="date" name="date" value="{{$order_hs->order_date}}" class="ml-2 h-8 w-32 text-sm bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_hs->order_date}}
                                        </div>
                                    </div>
                                    </div>
                                    <div class="flex ">
                                    <div class="relative ">
                                        <label for="user_name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">投稿者</label>

                                        <div  id="user_name" name="user_name" value="{{$order_hs->name}}" class="h-8 text-sm w-32 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_hs->name}}
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <label for="sh_name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">店名</label>
                                        <div  id="sh_name" name="sh_name" value="{{$order_hs->shop_name}}" class="ml-2 h-8 text-sm w-48 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$order_hs->shop_name}}
                                        </div>
                                    </div>
                                    </div>
                                    </div>

                                    <div class="mb-2 ml-0 mt-4 md:flex md:mb-4">
                                        {{-- <label for="status" class="mr-5 leading-7 text-sm  text-gray-800 ">Status指定</label> --}}
                                        <select class="w-32 h-8 rounded text-sm pt-1" id="status_id" name="status_id"  class="border">
                                        <option value="{{ $order_hs->status_id }}" @if(\Request::get('status_id') == '0') selected @endif >{{ $order_hs->status }}</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}" @if(\Request::get('status_id') == $status->id) selected @endif >{{ $status->status }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class=" mx-auto">
                                        <div class="relative">
                                          <label for="comment" class="leading-7  text-sm mt-2 text-gray-800 dark:text-gray-200 ">comment</label>
                                          <textarea id="comment" name="comment" rows="5" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $order_hs->comment }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <input type="hidden" id="user_id2" name="user_id2" value="{{$order_hs->user_id}}" class="h-8 text-sm w-32 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">
                         <input type="hidden" id="order_id2" name="order_id2" value="{{$order_hs->id}}" class="h-8 text-sm w-32 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        <div class="p-2 w-1/2 mx-auto">

                            <div class="p-2 w-full mt-4 flex justify-around">

                              <button type="submit" class="w-32 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded ">更新</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
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
