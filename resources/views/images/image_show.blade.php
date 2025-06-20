<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            商品画像<br>
        </h2>

        <div class="flex">

            <div class="mt-4">
                <input type="hidden" class="pl-0 ml-0 md:ml-2 w-32 h-6 items-center bg-gray-100 border rounded" name="sku_id"  value="{{ $image->id }}"/>
                <div class="p-0 w-full ml-6 flex mt-2 md:mt-0">
                    <button type="button" onclick="window.location.href='{{ url()->previous()}}'" class="w-40 h-8 text-white text-sm bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded ">戻る</button>
                    {{-- <button  onclick="location.href='{{ route('hinban_image_index')}}'" class="w-40 h-8 ml-6 text-white text-sm bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded ">画像リスト</button> --}}
                </div>
            </div>
            </div>

            {{-- <div class="p-2 w-full ml-4 flex mt-0">
                @if($login_user->role_id <= 2 )
                <form id="delete_{{$image->hinban_id}}" method="POST" action="{{ route('admin.image_destroy',['hinban'=>$image->hinban_id]) }}">
                    @csrf
                    @method('delete')
                    <div class="ml-0 mt-2 md:ml-4 md:mt-0">
                        <div class="w-32 h-8 text-center text-sm text-white bg-red-500 border-0 pt-2 px-2 focus:outline-none hover:bg-red-700 rounded ">
                        <a href="#" data-id="{{ $image->hinban_id }}" onclick="deletePost(this)" >削除</a>
                        </div>
                    </div>
                </form>
                @endif
            </div> --}}


    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 bg-white border-b border-gray-200">
                    <div class="text-gray-700 text-sm"> 品番：{{ $image->hinban_id  }}</div>
                    <div class="text-gray-700 text-sm"> 品名：{{ Str::limit($image->hinban_name,20)  }}</div>
                    <div class="text-gray-700 text-sm"> col：{{ $image->col_id  }} 　　sz: {{ $image->size_id  }}</div>
                    <div class="flex">
                        <div class="text-gray-700 text-sm ml-0 mr-4"> 概算コスト：{{ ($image->local_cur_price * $ex_rate->ex_rate / 100 * $cost_rate->cost_rate / 100) ?: ''  }}円</div>
                    </div>
                    <img class="w-full mx-auto" src="{{ asset('storage/sku_images/'.$image->sku_image) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="flex flex-wrap">
        @foreach ($sku_images as $image )
        <div class="w-1/3 md:w-1/4 p-2 md:p-4">

            <div class="text-gray-700"> Col:　{{ $image->col_id  }}</div>
            <x-sku_image-thumbnail :filename="$image->filename"  />

        </div>
        @endforeach
    </div> --}}


        <script>
            function deletePost(e) {
            'use strict';
            if (confirm('本当に削除してもいいですか?')) {
            document.getElementById('delete_' + e.dataset.id).submit();
            }
            }
        </script>


</x-app-layout>

