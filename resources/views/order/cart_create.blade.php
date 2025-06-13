<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            発注
            {{-- <button type="button" onclick="location.href='{{ route('company.index') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">戻る</button> --}}
        </h2>
        <x-flash-message status="session('status')"/>

        <div class="md:flex">
            <div class="flex">
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
            </div>
            <div class="ml-4 mt-2 md:ml-4 md:mt-2">
                <button type="button" class="w-32 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('order_index') }}'" >発注リスト</button>
            </div>
            </div>
            <div class="mt-2 ml-6 md:ml-4">
                <button type="button" class="w-32 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('cart_index') }}'" >カートを見る</button>
            </div>

        </div>
        <form method="get" action="{{ route('cart_create')}}" class="mt-4">

            <div class="lg:flex">
                <div class="md:flex">
                    <label for="year_code" class="items-center text-sm mt-2 text-gray-800 leading-tight" >年度CD：</label>
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
                        <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->id  }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="md:flex">
                    <label for="season_code" class="items-center text-sm mt-2 text-gray-800 leading-tight" >季節CD：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-4 mb-2 border " id="season_code" name="season_code" type="number" >
                    <option value="" @if(\Request::get('season_code') == '0') selected @endif >指定なし</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season->season_id }}" @if(\Request::get('season_code') == $season->season_id ) selected @endif >{{ $season->season_name  }}</option>
                    @endforeach
                    </select>
                    <label for="unit_code" class="items-center text-sm mt-2  text-gray-800 leading-tight" >Unit：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-4 mb-2 border " id="unit_code" name="unit_code" type="number" >
                    <option value="" @if(\Request::get('unit_code') == '0') selected @endif >指定なし</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->unit_code }}" @if(\Request::get('unit_code') == $unit->unit_code ) selected @endif >{{ $unit->id  }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="flex">
                <div>
                    <label for="face" class="mr-4 md:mr-0 items-center text-sm mt-2  text-gray-800 leading-tight" >Face：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-4 mb-2 border " id="face" name="face" type="text" >
                    <option value="" @if(\Request::get('face') == '0') selected @endif >指定なし</option>
                    @foreach ($faces as $face)
                        <option value="{{ $face->face }}" @if(\Request::get('face') == $face->face ) selected @endif >{{ $face->face  }}</option>
                    @endforeach
                    </select>
                </div>
                @if($logIn_user->role_id <10)
                <div>
                    <label for="type1" class="mr-1 leading-7 text-sm  text-gray-800 ">表示：</label>
                    <select id="type1" name="type1" class="w-28 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
                        <option value="a" @if(\Request::get('type1') == '0' || \Request::get('type1') == "a") selected @endif >全品番</option>
                        <option value="s" @if(\Request::get('type1') == "s") selected @endif>売上品番</option>
                    </select>
                </div>
                @endif
                </div>
                </div>
                <div class="flex">
                    <label for="hinban_code" class="items-center text-sm mt-2 mr-6 text-gray-800 leading-tight" >品番：</label>
                    <input class="w-36 h-8 rounded text-sm pt-1" id="hinban_code" placeholder="品番入力（一部でも可）" name="hinban_code" type="number" class="border">
                    <div>
                    <button  type="button" class="w-12 h-8 ml-2 text-sm text-center text-gray-900 bg-gray-200 border-0 py-0 px-2 focus:outline-none hover:bg-gray-300 rounded">検索</button>
                    </div>
                    <div class="pl-2 mt-0 md:mt-0 md:ml-0 ml-0 ">
                        <button type="button" class="w-16 h-8 bg-blue-500 text-sm text-white ml-0 hover:bg-blue-600 rounded lg:ml-2 " onclick="location.href='{{ route('cart_create') }}'" >全表示</button>
                    </div>
                </div>
        </form>
    </x-slot>


    @if(\Request::get('year_code') =='0')

    <div class="py-6 border">
        <div class="mx-auto sm:px-4 lg:px-4 border">
            <table>
                <thead>
                    <tr>
                        <th class="w-3/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">SKU</th>
                        {{-- <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Col</th> --}}
                        {{-- <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Size</th> --}}
                        <th class="w-5/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">商品名</th>
                        <th class="w-2/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">売価</th>
                        <th class="w-2/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">売数</th>
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
                            <td class="w-5/15 md:px-4 py-1">{{ Str::limit($product->hinban_name,16) }}</td>
                            <td class="w-2/15 md:px-4 py-1">{{ $product->m_price }}</td>
                            <td class="w-2/15 md:px-4 py-1">{{ $product->sales_pcs }}</td>
                            {{-- <td class="w-2/15 md:px-4 py-1">{{ $product->stocks }}</td> --}}
                            <td class="w-4/15 md:px-4 py-1">
                                <form method="POST" action="{{ route('cart_add') }}">
                                @csrf
                                <div class="flex">
                                <input type="hidden" name="sku_id" value="{{ $product->id }}">

                                <div class="w-full ">
                                <x-sku2_image-thumbnail :filename="$product->filename"  />
                                </div>

                                @if($product->stocks > $order_stop->order_stop_pcs)
                                <div class="mt-1">
                                <div class="mt-1 md:mt-16">
                                <select name="pcs" class="rounded h-12">
                                    <option value="{{ $product->pcs }}">{{ $product->pcs ?? 0}}</option>
                                    @for ($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                </div>
                                <div class="mt-0 md:mt-16 h-12">
                                <button type="submit" class="bg-indigo-500 text-white rounded text-sm ml-0 mt-1 h-9 w-16">追加</button>
                                </div>
                                </div>
                                @else
                                <td class="w-2/15 md:px-4 text-red-600 py-1">在庫なし</td>
                                @endif
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
            'season_code'=>\Request::get('season_code'),
            'unit_code'=>\Request::get('unit_code'),
            'face'=>\Request::get('face'),
            'hinban_code'=>\Request::get('hinban_code'),
            'type1'=>\Request::get('type1'),
            ])->links()}}
        </div>

    @else

    <div class="py-6 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border">
        <table>
            <thead>
                <tr>
                    <th class="w-3/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">SKU</th>
                    {{-- <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Col_ID</th> --}}
                    {{-- <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Size_ID</th> --}}
                    <th class="w-5/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">商品名</th>
                    <th class="w-2/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">売価</th>
                    <th class="w-2/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">売数</th>
                    {{-- <th class="w-1/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">在庫</th> --}}
                    {{-- <th class="w-4/16 md:pr-4 pr-0 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数量</th> --}}
                    {{-- <th class="w-1/16 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td class="w-3/15 md:px-4 py-1">{{ $product->hinban_id }}-{{ $product->col_id }}-{{ $product->size_id }}</td>
                        {{-- <td class="w-1/15 md:px-4 py-1">{{ $product->col_id }}</td> --}}
                        {{-- <td class="w-1/15 md:px-4 py-1">{{ $product->size_id }}</td> --}}
                        <td class="w-5/15 md:px-4 py-1">{{ Str::limit($product->hinban_name,24) }}</td>
                        <td class="w-2/15 md:px-4 py-1">{{ $product->m_price }}</td>
                        <td class="w-2/15 md:px-4 py-1">{{ $product->sales_pcs }}</td>
                        {{-- <td class="w-2/15 md:px-4 py-1">{{ $product->stocks }}</td> --}}
                        <td class="w-4/15 md:px-4 py-0">
                            <form method="POST" action="{{ route('cart_add') }}">
                                @csrf
                            <div class="flex">
                                <input type="hidden" name="sku_id" value="{{ $product->id }}">

                                {{-- <div class="w-full ">
                                <x-sku2_image-thumbnail :filename="$product->filename"  />
                                </div> --}}

                                @if(($product->filename))
                                <a href="{{ route('image_show2',['hinban'=>$product->hinban_id]) }}">
                                <x-sku2_image-thumbnail :filename="$product->filename"  />
                                </a>
                                @endif
                                @if(!($product->filename))
                                <x-sku2_image-thumbnail :filename="$product->filename"  />
                                @endif

                                @if($product->stocks > $order_stop->order_stop_pcs)
                                <div class="mt-1">
                                <div class="mt-1 md:mt-16">
                                <select name="pcs" class="rounded h-12">
                                    <option value="{{ $product->pcs }}">{{ $product->pcs ?? 0}}</option>
                                    @for ($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                </div>
                                <div class="mt-0 md:mt-16 h-12">
                                <button type="submit" class="bg-indigo-500 text-white rounded text-sm ml-0 mt-1 h-9 w-16">追加</button>
                                </div>
                                </div>
                                @else
                                <td class="w-2/15 md:px-4 text-red-600 py-1">在庫なし</td>
                                @endif
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
                'season_code'=>\Request::get('season_code'),
                'unit_code'=>\Request::get('unit_code'),
                'face'=>\Request::get('face'),
                'hinban_code'=>\Request::get('hinban_code'),
                'type1'=>\Request::get('type1'),
            ])->links()}}
        </div>
        </div>
    </div>
    @endif

    <script>
        const year = document.getElementById('year_code')
        year.addEventListener('change', function(){
        this.form.submit()
        })

        const brand = document.getElementById('brand_code')
        brand.addEventListener('change', function(){
        this.form.submit()
        })

        const season = document.getElementById('season_code')
        season.addEventListener('change', function(){
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

        const hinban = document.getElementById('hinban_code')
        hinban.addEventListener('change', function(){
        this.form.submit()
        })

        const type1 = document.getElementById('type1')
        type1.addEventListener('change', function(){
        this.form.submit()
        })

    </script>

</x-app-layout>
