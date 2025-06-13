<x-app-layout>
    <x-slot name="header">

        <h2 class="mb-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            発注カート
            {{-- <button type="button" onclick="location.href='{{ route('user.company.index') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">戻る</button> --}}
        </h2>
        <x-flash-message status="session('status')"/>
        <div class="md:flex">
        <div class="ml-2 flex mb-4 md:mb-2 md:ml-4">
        <div class="ml-0 mt-2 md:mt-0 md:ml-8">
            <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
        </div>
        <div class="ml-4 mt-2 md:ml-4 md:mt-0">
            <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('order_index') }}'" >発注リスト</button>
        </div>
        </div>
        <div class="flex">
        <div class="ml-2 mt-0 md:ml-4 md:mt-0">
            <button type="button" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:greenigo-700 rounded " onclick="location.href='{{ route('cart_edit') }}'" >カート修正</button>
        </div>
        <div class="ml-4 mt-0 md:ml-4 md:mt-0">
            <button type="button" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('cart_create') }}'" >オーダーを続ける</button>
        </div>
        </div>
        </div>

        <form method="post" action="{{ route('order_confirm')}}" >
            @csrf
            <div class="px-2 w-full md:ml-10 mt-4 mb-4 flex ">
                <button type="submit" class="w-32 h-7 text-center text-sm text-white bg-pink-500 border-0 py-1 px-2 focus:outline-none hover:bg-pink-700 rounded ">発注確定</button>
            </div>
            <div class="md:flex">
                <div class="flex">
                    <div class="flex">
                        <div class="pl-0 mt-0">
                            <label for="user_id" class="md:ml-2 leading-7 text-sm  text-gray-800 ">User_ID</label>
                            <div class="pl-2 ml-0 md:ml-2 w-16 h-6 text-sm items-center bg-gray-100 border rounded" name="user_id"  value="">{{ $user->id }}</div>
                        </div>
                        <div class="ml-2 pl-2 mt-0">
                            <label for="user_name" class="leading-7 text-sm  text-gray-800 ">発注者</label>
                            <div class="pl-2 w-36 h-6 text-sm items-center bg-gray-100 border rounded" name="user_name" value="">{{ $user->name }}</div>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <div class="pl-0 mt-0 md:pl-2 md:mt-0 ">
                        <label for="shop_id" class="leading-7 text-sm  text-gray-800 ">店コード</label>
                        <div class="pl-2 w-16 h-6 text-sm items-center bg-gray-100 border rounded" name="shop_id" value="">{{ $user->shop_id }}</div>
                    </div>
                    <div class="pl-2 mt-0 md:mt-0 ">
                        <label for="shop_name" class="leading-7 text-sm  text-gray-800 ">店名</label>
                        <div class="pl-2 w-40 h-6 text-sm items-center bg-gray-100 border rounded" name="shop_name" value="">{{ $user->shop_name }}</div>
                    </div>
                </div>
                <div class="flex">
                    <div class="md:pl-2 mt-0">
                        <label for="total_pcs" class="leading-7 text-sm  text-gray-800 ">合計数</label>
                        <div disable class="pl-2 w-24 h-6 text-sm items-center bg-gray-100 border rounded" name="total_pcs" value=""> {{ $cart_total->total_pcs ?? 0}} 枚</div>
                    </div>
                </div>
            </div>
            <div class="flex">
                <div class="flex">
                    <div class="md:pl-2 mt-0">
                        <label for="total_pcs" class="leading-7 text-sm  text-gray-800 ">売価合計</label>
                        <div disable class="pl-2 w-24 h-6 text-sm items-center bg-gray-100 border rounded" name="total_pcs" value=""> {{ $cart_total->total_kingaku ?? 0}} 円</div>
                    </div>
                </div>
                <div class="ml-2 flex">
                    <div class="md:pl-2 mt-0">
                        <label for="total_pcs" class="leading-7 text-sm  text-gray-800 ">原価合計</label>
                        <div disable class="pl-2 w-24 h-6 text-sm items-center bg-gray-100 border rounded" name="total_pcs" value=""> {{ $cart_total->total_gedai ?? 0}} 円</div>
                    </div>
                </div>
            </div>
        </form>
    </x-slot>

    <div class="py-0 border">
        {{-- <h1>店舗Report</h1> --}}
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table>
                <thead>
                    <tr>
                        {{-- <th>商品ID</th> --}}
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">SKU</th>
                        {{-- <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ColID</th> --}}
                        {{-- <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">SizeID</th> --}}
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">商品名</th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">売価</th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数量</th>
                        {{-- <th>操作</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carts as $cart)
                        <tr>
                            {{-- <td>{{ $cart->sku_id }}</td> --}}
                            <td class="w-3/15 md:px-4 py-1">{{ $cart->hinban_id }}-{{ $cart->col_id }}-{{ $cart->size_id }}</td>
                            {{-- <td class="w-1/15 md:px-4 py-1">{{ $cart->col_id }}</td> --}}
                            {{-- <td class="w-1/15 md:px-4 py-1">{{ $cart->size_id }}</td> --}}
                            <td class="w-1/15 md:px-4 py-1">{{ $cart->hinban_name }}</td>
                            <td class="w-1/15 md:px-4 py-1">{{ $cart->m_price }}</td>
                            <td class="w-1/15 md:px-4 py-1">
                                <form method="POST" action="{{ route('cart_add') }}">
                                    @csrf
                                    <input type="hidden" name="sku_id" value="{{ $cart->sku_id }}">
                                    <input type="hidden" name="user_id" value="{{ $cart->user_id }}">
                                    <input type="hidden" name="shop_id" value="{{ $cart->shop_id }}">
                                    <input disable class="w-12 rounded bg-gray-100" type="text" name="pcs" value="{{ $cart->pcs }}">

                                    {{-- <button type="submit">カートに追加</button> --}}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>


</x-app-layout>
