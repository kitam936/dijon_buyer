<x-app-layout>
    <x-slot name="header">
        {{--  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Menu
        </h2><br>   --}}


        <h3 class="ml-8 font-semibold text-xl text-indigo-800 leading-tight">
        Menu
        </h3>

        <div class="md:flex ">
        <div class="flex ml-8 p-1 text-gray-900  ">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('data.predata_index') }}'" >事前品番Data</button>
            <button type="button" class="ml-2 h-8 w-40 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('hinban.hinban_index2') }}'" >商品</button>
        </div>

         <div class="ml-8 md:ml-0 md:flex p-1 text-gray-900  ">
            <button type="button" class="w-40 h-8 text-center text-sm text-gray-300 bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href=''" >発注</button>
            <button type="button" class="ml-1 md:ml-2 h-8 w-40 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('data.rate_menu') }}'" >Rate</button>
        </div>
        </div>

        <div class="md:flex mt-4">
        <div class="flex ml-8 p-1 text-gray-900  ">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('memberlist') }}'" >STAFF</button>
            <button type="button" class="ml-2 h-8 w-40 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('vendor_index') }}'" >仕入先</button>
        </div>

        <div class="ml-8 md:ml-0 md:flex p-1 text-gray-900  ">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('data.data_menu') }}'" >Data管理</button>
            <button type="button" class="ml-1 md:ml-2 h-8 w-40 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href=''" >＊＊＊</button>
        </div>
        </div>

        <br>

        <br>

        {{-- <h3 class="ml-8 font-semibold text-xl text-indigo-800 leading-tight">
            業務Menu
        </h3> --}}

        {{-- @if($dl_new_order) --}}
        <div class="ml-12 text-ml text-red-500">
            ※　発注の新着Dataがあります !
        </div>
        {{-- @endif --}}




        <div class="flex ">
            <div class="flex ml-8 p-1 text-gray-900  ">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('manual_download') }}'" >マニュアルDL</button>
                {{-- <button type="button" class="ml-2 md:ml-2 h-8 w-40 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('manual_download') }}'" >マニュアルDL</button> --}}
            </div>

            <div class="ml-1  md:ml-2 mt-1 ">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-40 h-8 text-sm bg-gray-500 text-white ml-0 md:ml-0 hover:bg-gray-600 rounded">ログアウト</button>
                </form>
            </div>
        </div>
            </div>




    </x-slot>





</x-app-layout>
