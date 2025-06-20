<x-app-layout>
    <x-slot name="header">

        <h2 class="mb-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            発注カート
            {{-- <button type="button" onclick="location.href='{{ route('user.company.index') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">戻る</button> --}}
        </h2>

        <x-flash-message status="session('status')"/>
        <x-validation-errors class="mb-4" />

        <div class="md:flex">
        <div class="ml-2 flex mb-4 md:mb-2 md:ml-4">
        <div class="ml-0 mt-2 md:mt-0 md:ml-8">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('menu') }}'" >Menu</button>
        </div>
        <div class="ml-4 mt-2 md:ml-4 md:mt-0">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('order_index') }}'" >発注Data</button>
        </div>
        </div>
        <div class="flex">
        <div class="ml-2 mt-0 md:ml-4 md:mt-0">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:greenigo-700 rounded " onclick="location.href='{{ route('cart_edit') }}'" >カート修正</button>
        </div>
        <div class="ml-4 mt-0 md:ml-4 md:mt-0">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('cart_create') }}'" >オーダーを続ける</button>
        </div>
        </div>
        </div>

        <div class="flex mb-2 mt-4">

            <div class="">
                {{-- <div class="pl-0 mt-0">
                    <label for="user_id" class="md:ml-2 leading-7 text-sm  text-gray-800 ">User_ID</label>
                    <div class="pl-2 ml-0 md:ml-2 w-16 h-6 text-sm items-center bg-gray-100 border rounded" name="user_id"  value="">{{ $user->id }}</div>
                </div> --}}
                <div class=" ml-0 pl-0 mt-0">
                    <label for="user_name" class="leading-7 text-sm  text-gray-800 ">発注者</label>
                    <div class="pl-2 ml-0 w-36 h-6 text-sm items-center bg-gray-100 border rounded" name="user_name" value="">{{ $user->name }}</div>
                </div>
            </div>



        </div>
        <div class="flex mb-4">
            <div class="flex">
                <div class="md:pl-2 mt-0">
                    <label for="total_pcs" class="leading-7 text-sm  text-gray-800 ">合計数</label>
                    <div disable class="pl-2 w-24 h-6 text-sm items-center bg-gray-100 border rounded" name="total_pcs" value=""> {{ $cart_total->total_pcs ?? 0}} 枚</div>
                </div>
            </div>
            <div class="ml-2 flex">
                <div class="md:pl-2 mt-0">
                    <label for="local_cur_total" class="leading-7 text-sm  text-gray-800 ">現地コスト計</label>
                    <div disable class="pl-2 w-32 h-6 text-sm items-center bg-gray-100 border rounded" name="local_cur_total" value=""> {{ $cart_total->local_cur_total ?? 0}} </div>
                </div>
            </div>
            {{-- <div class="ml-2 flex">
                <div class="md:pl-2 mt-0">
                    <label for="local_yen_total" class="leading-7 text-sm  text-gray-800 ">円建コスト計</label>
                    <div disable class="pl-2 w-24 h-6 text-sm items-center bg-gray-100 border rounded" name="local_yen_total" value=""> {{ $cart_total->local_yen_total ?? 0}} 円</div>
                </div>
            </div> --}}
            <div class="ml-2 flex">
                <div class="md:pl-2 mt-0">
                    <label for="expected_total" class="leading-7 text-sm  text-gray-800 ">概算コスト計</label>
                    <div disable class="pl-2 w-32 h-6 text-sm items-center bg-gray-100 border rounded" name="expected_total" value=""> {{ $cart_total->expected_total ?? 0}} 円</div>
                </div>
            </div>
        </div>

        <form method="post" action="{{ route('order_confirm')}}" >
            @csrf
            <div class="mb-4 ml-0 flex">
                <label for="vendor_id" class="mr-1 leading-7 text-sm  text-gray-800 ">仕入先:</label>
                <select class="w-32 h-8 rounded border text-sm items-center pt-1" id="vendor_id" name="vendor_id" >
                    <option value="" @if(\Request::get('vendor_id') == '0') selected @endif >仕入先選択</option>
                    @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}" @if(\Request::get('vendor_id') == $vendor->id ) selected @endif >{{ $vendor->vendor_name  }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex">

            <label><input type="text" name="memo" style="width:300px;" class="rounded w-50 ml-2" placeholder="memo  欄"></label>

            <div class="w-full ml-10 md:ml-10 mt-1 mb-4">
                <button type="submit" class="w-24 h-8 text-center text-sm text-white bg-pink-500 border-0 py-1 px-2 focus:outline-none hover:bg-pink-700 rounded ">発注確定</button>
            </div>
            </div>
        </form>

    </x-slot>

    {{-- <div class="ml-0 mt-3 py-4 md:w-full border">
        <div class=" w-full  sm:px-0 lg:px-0 border mt-0 ml-0">
            <div class='md:w-full pl-0 border bg-gray-100 h-6 text-sm'>
                総数：{{ number_format(($cart_total->total_pcs ?? 0)) }}枚　
                現地通貨計：{{ number_format(($cart_total->local_cur_total ?? 0))}} 円　
                想定コスト計：{{ number_format(($cart_total->expected_total ?? 0))}} 円
            </div>

        </div>
    </div> --}}



    <div class="py-4 border ">
        {{-- <h1>店舗Report</h1> --}}
        <div class="bg-white mx-auto  border ">
            <table>
                <thead>
                    <tr>
                        {{-- <th>商品ID</th> --}}
                        <th class="w-3/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">SKU</th>
                        {{-- <th class="w-1/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ColID</th> --}}
                        <th class="w-3/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">先方品番</th>
                        <th class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">現地コスト</th>
                        <th class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">想定コスト</th>
                        <th class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数量</th>
                        <th class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">　</th>
                        {{-- <th>操作</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carts as $cart)
                        <tr>
                            {{-- <td>{{ $cart->sku_id }}</td> --}}
                            <td class="w-3/12 md:px-4 py-1 text-center">{{ $cart->hinban_id }}-{{ $cart->col_id }}-{{ $cart->size_id }}</td>
                            {{-- <td class="w-1/12 md:px-4 py-1">{{ $cart->col_id }}</td> --}}
                            <td class="w-3/12 md:px-4 py-1 text-center">{{ $cart->prod_code }}</td>
                            <td class="w-2/12 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums">{{ $cart->local_cur_price }}</span></td>
                            <td class="w-2/12 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums">{{ $cart->local_cur_price * $ex_rate->ex_rate/100 *$cost_rate->cost_rate/100}}</span></td>
                            <td class="w-2/12 md:px-4 py-1 text-right"><span style="font-variant-numeric:tabular-nums">{{ $cart->pcs }}</span></td>
                            {{-- <td class="w-1/12 md:px-4 py-1"> --}}
                                {{-- <form method="POST" action="{{ route('cart_add') }}">
                                    @csrf
                                    <input type="hidden" name="sku_id" value="{{ $cart->sku_id }}">
                                    <input type="hidden" name="user_id" value="{{ $cart->user_id }}">

                                    <input readonly class="w-12 rounded bg-gray-100" type="text" name="pcs" value="{{ $cart->pcs }}">


                                </form> --}}
                            {{-- </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


</x-app-layout>
