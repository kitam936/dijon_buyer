
<x-app-layout>
    <x-slot name="header">

        <h2 class="mb-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            仕入先詳細
            {{-- <button type="button" onclick="location.href='{{ route('user.vendormpany.index') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">戻る</button> --}}
        </h2>
        <div class="ml-2 md:flex mb-4 md:ml-4">
        <div class="ml-2 flex mb-4 md:ml-4">
        <div class="ml-0 mt-2 md:mt-0 md:ml-8">
            <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('vendor_index') }}'" >vendor一覧</button>
        </div>

        <div class="ml-4 mt-2 md:ml-4 md:mt-0">
            <button type="button" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('vendor_edit',['id'=>$vendor->id]) }}'" >編集</button>
        </div>
        </div>

        <div>
        <form id="delete_{{$vendor->id}}" method="POST" action="{{ route('vendor_destroy',['id'=>$vendor->id]) }}">
            @csrf
            @method('delete')
            <div class="ml-2 mt-0 md:ml-4 md:mt-0">
                <div  class="w-32 text-center text-sm text-white bg-red-500 border-0 py-1 px-2 focus:outline-none hover:bg-red-600 rounded ">
                <a href="#" data-id="{{ $vendor->id }}" onclick="deletePost(this)" >仕入先削除</a>
                </div>
            </div>
        </form>
        </div>
        </div>




        <form method="get" action="" class="mt-2 mb-4">

            <div class="">

                <div class="">
                <div class="flex pl-0 mt-0">
                    <label for="vendor_id" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">code</label>
                    <div class="pl-2 ml-0 md:ml-2 w-24 h-6 text-sm items-center bg-gray-100 border rounded" name="vendor_id"  value="">{{ $vendor->id }}</div>
                </div>

                <div class="flex pl-0 mt-2 md:mt-2 ">
                    <label for="vendor_name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">社名</label>
                    <div class="ml-3 pl-2 w-32 h-6 text-sm items-center bg-gray-100 border rounded" name="vendor_name" value="">{{ $vendor->vendor_name }}</div>
                </div>
                </div>
                <div class="flex">
                {{--  <div class="flex pl- mt-1 md:mt-0">

                    <div class="pl-2 w-32 h-6 items-center bg-gray-100 border rounded md:ml-2" name="vendor_id" value="">{{ $vendor->id }}</div>
                </div>  --}}

                </div>
                <div class="flex">
                <div class="flex pl-0 mt-2 md:mt-2">
                    <label for="vendor_info" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">info</label>
                    <div class="pl-2 ml-2 text-sm md:ml-4 w-80 h-6 items-center bg-gray-100 border rounded" name="vendor_info" value="">{{ $vendor->vendor_info }}</div>
                </div>

                </div>

            </div>
     </form>

     <script>
        function deletePost(e) {
        'use strict';
        if (confirm('本当に削除してもいいですか?')) {
        document.getElementById('delete_' + e.dataset.id).submit();
        }
        }
    </script>

    </x-slot>


</x-app-layout>
