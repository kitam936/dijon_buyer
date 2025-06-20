<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            発注
            {{-- <button type="button" onclick="location.href='{{ route('company.index') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">戻る</button> --}}
        </h2>
        <x-flash-message status="session('status')"/>

        <x-validation-errors class="mb-4" />

        <div class="md:flex">
            <div class="flex">
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('menu') }}'" >Menu</button>
            </div>
            <div class="ml-4 mt-2 md:ml-4 md:mt-2">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('order_index') }}'" >発注Data</button>
            </div>
            </div>
            <div class="flex">
            <div class="mt-2 ml-6 md:ml-4">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('cart_index') }}'" >カートを見る</button>
            </div>
            <div class="mt-2 ml-4 md:ml-4">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('hinban.hinban_index2') }}'" >商品登録Data</button>
            </div>
            </div>

        </div>

        <form method="get" action="{{ route('cart_create')}}" class="mt-4">

            <div class="lg:flex">
                <div class="md:flex">
                    <label for="year_code" class="items-center text-sm mt-2 text-gray-800 leading-tight" >年CD：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-2 mb-2" id="year_code" name="year_code" type="number" class="border">
                    <option value="" @if(\Request::get('year_code') == '0') selected @endif >指定なし</option>
                    @foreach ($years as $year)
                        <option value="{{ $year->year_code }}" @if(\Request::get('year_code') == $year->year_code ) selected @endif >{{ $year->year_code  }}</option>
                    @endforeach
                    </select>
                    <label for="brand_code" class="items-center text-sm mt-2  text-gray-800 leading-tight" >Brand：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 border mb-2 mr-4 " id="brand_code" name="brand_code" type="number" >
                    <option value="" @if(\Request::get('brand_code') == '0') selected @endif >指定なし</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->id  }}={{ $brand->brand_name  }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="flex">

                    <label for="unit_code" class="items-center mr-3 text-sm mt-2  text-gray-800 leading-tight" >Unit：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-3 mb-2 border " id="unit_code" name="unit_code" type="number" >
                    <option value="" @if(\Request::get('unit_code') == '0') selected @endif >指定なし</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" @if(\Request::get('unit_code') == $unit->id ) selected @endif >{{ $unit->id }}={{ $unit->season_name }}</option>
                    @endforeach
                    </select>

                <div class="flex">

                    <label for="face" class="mr-2 md:mr-0 items-center text-sm mt-2  text-gray-800 leading-tight" >Face：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-4 mb-2 border " id="face" name="face" type="text" >
                    <option value="" @if(\Request::get('face') == '0') selected @endif >指定なし</option>
                    @foreach ($faces as $face)
                        <option value="{{ $face->face_code }}" @if(\Request::get('face') == $face->face_code ) selected @endif >{{ $face->face_code  }}={{ $face->face_item  }}</option>
                    @endforeach
                    </select>
                </div>
                </div>

                <div class="flex">

                    <div class="pl-2 mt-0 md:mt-0 md:ml-4 ml-12 ">
                        <button type="button" class="w-20 h-8 bg-blue-500 text-sm text-white ml-0 hover:bg-blue-600 rounded lg:ml-2 " onclick="location.href='{{ route('cart_create') }}'" >全表示</button>
                    </div>
                    <div class="mt-0 md:mt-0 ml-7">
                        <label for="type1" class="mr-1 md:mr-1 leading-7 text-sm  text-gray-800 ">表示：</label>
                        <select id="type1" name="type1" class="w-24 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
                            {{-- <option value="" @if(\Request::get('order') == '0' ) selected @endif >選択</option> --}}
                            <option value="y" @if(\Request::get('type1') == "y" || \Request::get('type1') == '0') selected @endif >未発注</option>
                            <option value="a" @if(\Request::get('type1') == "a") selected @endif>全品番</option>
                        </select>
                    </div>
                </div>

                </div>


        </form>
    </x-slot>



    <div class="py-6 border">
        <div class="mx-auto sm:px-4 lg:px-4 border">
            <table>
                <thead>
                    <tr>
                        <th class="w-3/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">SKU</th>
                        {{-- <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Col</th> --}}
                        {{-- <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Size</th> --}}
                        <th class="w-5/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">先方品番</th>
                        <th class="w-2/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">想定コスト</th>
                        {{-- <th class="w-2/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数</th> --}}
                        {{-- <th class="w-1/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">在庫</th> --}}
                        {{-- <th class="w-4/16 md:pr-4 pr-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数量</th> --}}
                        {{-- <th class="w-1/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="w-3/15 md:px-4 py-1">{{ $product->hinban_id }}-{{ $product->col_id }}-{{ $product->size_id }}</td>
                            {{-- <td class="w-1/15 md:px-4 py-1">{{ $cart->col_id }}</td> --}}
                            {{-- <td class="w-1/15 md:px-4 py-1">{{ $cart->size_id }}</td> --}}
                            <td class="w-5/15 md:px-4 py-1">{{ $product->prod_code }}</td>
                            <td class="w-2/15 md:px-4 py-1 text-center"><span style="font-variant-numeric:tabular-nums">{{ $product->local_cur_price * $ex_rate->ex_rate/100 *$cost_rate->cost_rate/100}}</span></td>
                            {{-- <td class="w-2/15 md:px-4 py-1">{{ $product->pcs }}</td> --}}
                            {{-- <td class="w-2/15 md:px-4 py-1">{{ $product->stocks }}</td> --}}
                            <td class="w-4/15 md:px-4 py-1">
                                <form method="POST" action="{{ route('cart_add') }}">
                                @csrf
                                <div class="flex">
                                <input type="hidden" name="sku_id" value="{{ $product->sku_id }}">
                                <div class="w-full ">
                                <x-sku2_image-thumbnail :filename="$product->sku_image"  />
                                </div>


                                <div class="mt-1 ml-2">
                                <div class="mt-1 md:mt-16">
                                    <input type="number" class="w-20 h-10 text-right bg-white border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="pcs" value="{{ $product->pcs ?? 0 }}"></input>
                                {{-- <select name="pcs" class="rounded h-12">
                                    <option value="{{ $product->pcs }}">{{ $product->pcs ?? 0}}</option>
                                    @for ($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor --}}
                                </select>
                                </div>
                                <div class="mt-0 md:mt-16 h-12">
                                <button type="submit" class="bg-indigo-500 text-white rounded text-sm ml-0 mt-1 h-9 w-16">追加</button>
                                </div>
                                </div>

                            </div>
                            </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{  $products->appends([
            'year_code'=>\Request::get('year_code'),
            'brand_code'=>\Request::get('brand_code'),
            // 'season_code'=>\Request::get('season_code'),
            'unit_code'=>\Request::get('unit_code'),
            'face'=>\Request::get('face'),
            'hinban_code'=>\Request::get('hinban_code'),
            'type'=>\Request::get('type'),
            ])->links()}}
        </div>


    </div>


    <script>
        const year = document.getElementById('year_code')
        year.addEventListener('change', function(){
        this.form.submit()
        })

        const brand = document.getElementById('brand_code')
        brand.addEventListener('change', function(){
        this.form.submit()
        })

        const unit = document.getElementById('unit_code')
        unit.addEventListener('change', function(){
        this.form.submit()
        })

        const face = document.getElementById('face')
        face.addEventListener('change', function(){
        this.form.submit()
        })

        const type1 = document.getElementById('type1')
        type1.addEventListener('change', function(){
        this.form.submit()
        })

    </script>

</x-app-layout>
