
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            発注Data
        </h2>

        <x-flash-message status="session('status')"/>

        <div class="md:flex">
            <div class="flex">
            <div class="md:flex">
            <div class="pl-2 mt-2 ml-2 ">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('menu') }}'" >Menu</button>
            </div>

            <div class="pl-2 mt-2 ml-2 ">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('order_image_list') }}'" >発注済商品リスト</button>
            </div>
            </div>

            <div class="pl-2 mt-2 ml-2 ">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('cart_index') }}'" >カートを見る</button>
            </div>


            </div>

            <div class="flex">
                <div class="pl-2 ml-2 mt-2 md:ml-2 md:mt-2">
                    <button type="button" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('cart_create') }}'" >オーダー</button>
                </div>

                @if($dl_new)
                <div class="flex">
                <div class="pl-2 ml-2 mt-2 md:ml-2 md:mt-2">
                    <button type="button" class="w-40 h-8 text-center text-sm text-white bg-blue-500 binventory-0 py-1 px-2 focus:outline-none hover:bg-blue-700 rounded " onclick="location.href='{{ route('order_csv_all') }}'" >一括DL</button>
                </div>

                </div>
                @else
                <div class="pl-2 ml-4 mt-2 md:ml-4 md:mt-2 text-indigo-700">未ダウンロードのデータはありません</div>
                @endif

            </div>

        </div>




    </x-slot>


    <div class="py-6 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-2/3 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">id</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Date</th>

                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">発注者</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数計</th>
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">status</th>
                        @if(Auth::User()->role_id == 1)
                        <th class="w-3/14 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($all_order_hs as $order_h)
                    <tr>
                        <td class="w-1/14 md:px-4 py-1 text-center">  {{ $order_h->id }} </td>
                        <td class="w-3/14 text-sm md:px-4 py-1 text-center">
                            <a href="{{ route('order_detail',['order'=>$order_h->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  >{{\Carbon\Carbon::parse($order_h->order_date)->format("y/m/d")}}
                            </a> </td>

                        <td class="w-3/14 text-sm md:px-4 py-1 text-center">  {{ Str::limit($order_h->name,10) }} </td>
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $order_h->pcs }}</td>
                        @if($order_h->status_id == 1)
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-red-600 text-center"> {{ $order_h->status_name }}</td>
                        @endif
                        @if($order_h->status_id >2 && $order_h->status_id <7)
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-green-600 text-center"> {{ $order_h->status_name }}</td>
                        @endif
                        @if($order_h->status_id ==7)
                        <td class="w-3/14 pl-2 text-sm md:px-4 py-1 text-center"> {{ $order_h->status }}</td>
                        @endif
                        @if(Auth::User()->role_id == 1)
                        <td class="w-2/16 md:px-2 py-1">
                            <div>
                            <form method="POST" action="{{ route('order_result_destroy', $order_h->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-12 h-9 mt-1 items-center bg-red-500 text-sm text-white ml-0 hover:bg-red-600 rounded " onclick="return confirm('削除しますか？')">削除</button>
                            </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{-- {{ $order_hs->links() }} --}}
        </div>
    </div>
    {{-- <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div id="app">
            <analysis-component></analysis-component>

        </div>
    </body> --}}


</x-app-layout>
