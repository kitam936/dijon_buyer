<x-app-layout>
    <x-slot name="header">

        <h2 class="mb-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            発注カート修正
            {{-- <button type="button" onclick="location.href='{{ route('user.company.index') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">戻る</button> --}}
        </h2>
        <x-flash-message status="session('status')"/>

        <div class="md:flex">
        <div class="ml-2 flex mb-4 md:mb-2 md:ml-4">
        <div class="ml-0 mt-2 md:mt-0 md:ml-8">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('menu') }}'" >Menu</button>
        </div>
        <div class="ml-4 mt-2 md:ml-4 md:mt-0">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('cart_index') }}'" >カートに戻る</button>
        </div>
        </div>
        <div class="flex">
        {{-- <div class="ml-2 mt-0 md:ml-4 md:mt-0">
            <button type="button" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:greenigo-700 rounded " onclick="location.href='{{ route('cart_edit') }}'" >カート修正</button>
        </div> --}}
        <div class="ml-2 mt-0 md:ml-4 md:mt-0">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('cart_create') }}'" >オーダーを続ける</button>
        </div>
        </div>
        </div>

        <div class="flex mb-4 mt-4">

            <div class="flex">
                {{-- <div class="pl-0 mt-0">
                    <label for="user_id" class="md:ml-2 leading-7 text-sm  text-gray-800 ">User_ID</label>
                    <div class="pl-2 ml-0 md:ml-2 w-16 h-6 text-sm items-center bg-gray-100 border rounded" name="user_id"  value="">{{ $user->id }}</div>
                </div> --}}
                <div class="flex ml-0 pl-0 mt-0">
                    <label for="user_name" class="leading-7 text-sm  text-gray-800 ">発注者</label>
                    <div class="pl-2 ml-2 w-36 h-6 text-sm items-center bg-gray-100 border rounded" name="user_name" value="">{{ $user->name }}</div>
                </div>
            </div>
        </div>

    </x-slot>

    <div class="ml-0 mt-3 py-4 md:w-full border">
        <div class=" w-full  sm:px-0 lg:px-0 border mt-0 ml-0">
            <div class='md:w-full pl-0 border bg-gray-100 h-6 text-sm'>
                総数：{{ number_format(($cart_total->total_pcs ?? 0)) }}枚　
                現地通貨計：{{ number_format(($cart_total->local_cur_total ?? 0))}} 円　
                想定コスト計：{{ number_format(($cart_total->expected_total ?? 0))}} 円
            </div>
            {{-- <div class='md:w-1/2 pl-0 border bg-gray-100 h-6 text-sm'>
                円建コスト計：{{ number_format(($cart_total->local_yen_total ?? 0))}} 円　

            </div> --}}
        </div>
    </div>

    <div class="py-0 border">
        {{-- <h1>店舗Report</h1> --}}
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table>
                <thead>
                    <tr>
                        {{-- <th>商品ID</th> --}}
                        <th class="w-3/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">SKU</th>
                        {{-- <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Col</th> --}}
                        {{-- <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Size</th> --}}
                        <th class="w-4/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">先方品番</th>
                        <th class="w-2/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 text-left">現地コスト　　数</th>
                        {{-- <th class="w-2/15 md:pr-16 title-font tracking-wider pr-10 font-medium text-gray-900 text-sm bg-gray-100">数量</th> --}}
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carts as $cart)
                        <tr>
                            {{-- <td>{{ $cart->sku_id }}</td> --}}
                            <td class="w-3/15 md:px-4 py-1">{{ $cart->hinban_id }}-{{ $cart->col_id }}-{{ $cart->size_id }}</td>
                            {{-- <td class="w-1/15 md:px-4 py-1">{{ $cart->col_id }}</td> --}}
                            {{-- <td class="w-1/15 md:px-4 py-1">{{ $cart->size_id }}</td> --}}
                            <td class="w-4/15 md:px-4 py-1">{{ $cart->prod_code }}</td>
                            {{-- <td class="w-2/15 md:px-4 py-1">{{ $cart->local_cur_price * $ex_rate->ex_rate/100 *$cost_rate->cost_rate/100}}</td> --}}
                            <td class="w-2/15 md:px-0 py-1">
                                <form method="POST" action="{{ route('cart_update',['cart'=>$cart->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex">
                                    <input type="hidden" name="sku_id" value="{{ $cart->sku_id }}">
                                    <input type="hidden" name="user_id" value="{{ $cart->user_id }}">

                                    <input type="number" class="w-20 h-10 text-right bg-white border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="local_cur_price" value="{{ $cart->local_cur_price }}"></input>
                                    <input type="number" class="w-20 h-10 text-right bg-white border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="pcs" value="{{ $cart->pcs }}"></input>

                                    <button type="submit" class="w-12 h-9 mt-1 bg-blue-500 text-sm text-white ml-1 hover:bg-blue-600 rounded lg:ml-2 ">更新</button>
                                    </div>
                                </form>
                            </td>
                            <td class="w-1/15 md:px-0 py-1">
                                <!-- カートから削除 -->
                                <form method="POST" action="{{ route('cart_destroy', ['cart'=>$cart->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-12 h-9 mt-1 bg-red-500 text-sm text-white ml-0 hover:bg-red-600 rounded lg:ml-2 ">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>


</x-app-layout>
