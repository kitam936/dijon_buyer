<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2">
            画像リスト 　<br>
        </h2>
        <div class="md:flex  md:ml-4 mb-2">

            <div class="ml-4 flex mt-2 md:mt-0">
                <div class="ml-0 md:ml-4">
                    <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('hinban.hinban_index2') }}'" >登録済品番リスト</button>
                </div>

            </div>
        </div>

        <form method="get" action="{{ route('hinban_image_index')}}" class="mt-4">

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
                    <label for="unit_id" class="items-center text-sm mt-2  text-gray-800 leading-tight" >Unit：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-4 mb-2 border " id="unit_id" name="unit_id" type="number" >
                    <option value="" @if(\Request::get('unit_id') == '0') selected @endif >指定なし</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->unit_id }}" @if(\Request::get('unit_id') == $unit->unit_id ) selected @endif >{{ $unit->unit_id  }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="flex">
                <div class="flex">
                    <label for="face" class="mr-5 md:mr-0 items-center text-sm mt-2  text-gray-800 leading-tight" >Face：</label>
                    <select class="w-24 h-8 rounded text-sm pt-1 mr-4 mb-2 border " id="face" name="face" type="text" >
                    <option value="" @if(\Request::get('face') == '0') selected @endif >指定なし</option>
                    @foreach ($faces as $face)
                        <option value="{{ $face->face_code }}" @if(\Request::get('face') == $face->face_code ) selected @endif >{{ $face->face_code  }}</option>
                    @endforeach
                    </select>
                    <div class="pl-2 mt-0 md:mt-0 md:ml-0 ml-0 ">
                        <button type="button" class="w-16 h-8 bg-blue-500 text-sm text-white ml-0 hover:bg-blue-600 rounded lg:ml-2 " onclick="location.href='{{ route('image_index') }}'" >全表示</button>
                    </div>
                </div>
                <div class="mt-0 md:mt-0 ml-4">
                    <label for="order" class="mr-1 md:mr-1 leading-7 text-sm  text-gray-800 ">並順：</label>
                    <select id="order" name="order" class="w-24 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
                        {{-- <option value="" @if(\Request::get('order') == '0' ) selected @endif >選択</option> --}}
                        <option value="u" @if(\Request::get('order') == "u" || \Request::get('order') == '0') selected @endif >新着順</option>
                        <option value="s" @if(\Request::get('order') == "s") selected @endif>SKU順</option>
                    </select>
                </div>

                </div>
                </div>




        </form>

    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-flash-message status="session('status')"/>

                    <div class="flex flex-wrap">
                    @foreach ($images as $image )
                    <div class="w-1/2 md:w-1/4 p-2 md:p-4">
                    {{-- <a href="{{ route('image.edit',['image'=>$image->id]) }}"> --}}
                    <div class="border rounded-md p-0 md:p-0">
                        {{-- <div class="text-gray-700 text-sm"> skuid：{{ $image->sku_id  }}</div> --}}
                        <div class="text-gray-700 text-sm"> 品番：{{ $image->hinban_id  }}</div>
                        <div class="text-gray-700 text-sm"> 品名：{{ Str::limit($image->hinban_name,20)  }}</div>
                        <div class="flex">
                            <div class="text-gray-700 text-sm ml-0 mr-4"> 想定コスト：{{ ($image->local_cur_price * $ex_rate->ex_rate / 100 * $cost_rate->cost_rate / 100) ?: ''  }}円</div>
                        </div>
                        @if(($image->sku_image))
                        <a href="{{ route('hinban_image_show',['id'=>$image->sku_id]) }}">
                        <x-sku_image-thumbnail :filename="$image->sku_image"  />
                        </a>
                        @endif
                        @if(!($image->sku_image))
                        <x-sku_image-thumbnail :filename="$image->sku_image"  />
                        @endif

                    </div>
                    {{-- </a> --}}
                    </div>
                    @endforeach
                    </div>
                    {{  $images->appends([
                        'year_code'=>\Request::get('year_code'),
                        'brand_code'=>\Request::get('brand_code'),
                        'season_code'=>\Request::get('season_code'),
                        'unit_id'=>\Request::get('unit_id'),
                        'face'=>\Request::get('face'),
                        'hinban_code'=>\Request::get('hinban_code'),
                        // 'type'=>\Request::get('type'),
                    ])->links()}}
                </div>
            </div>
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

        const season = document.getElementById('season_code')
        season.addEventListener('change', function(){
        this.form.submit()
        })

        const unit = document.getElementById('unit_id')
        unit.addEventListener('change', function(){
        this.form.submit()
        })

        const face = document.getElementById('face')
        face.addEventListener('change', function(){
        this.form.submit()
        })



        const order = document.getElementById('order')
        order.addEventListener('change', function(){
        this.form.submit()
        })

    </script>

</x-app-layout>
