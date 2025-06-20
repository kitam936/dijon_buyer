
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">

            <div>
            商品登録Data
            </div>
        </h2>
        <div class="md:flex">
            <div class="flex">
            <div class="w-40 ml-4 mt-4 text-sm items-right mb-0">
                <button onclick="location.href='{{ route('menu') }}'" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded">Menu</button>
            </div>
            <div class="w-40 ml-4 mt-4 text-sm items-right mb-0">
                <button onclick="location.href='{{ route('hinban_image_index') }}'" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded">画像リスト</button>
            </div>
            </div>
            <div class="flex">
            <div class="w-40 md:ml-4 ml-4 mt-4 text-sm items-right mb-0">
                <button onclick="location.href='{{ route('hinban_create') }}'" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded ">新規登録</button>
            </div>
            <div class="pl-2 ml-2 mt-4 md:ml-2 md:mt-4">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('cart_create') }}'" >オーダー画面へ</button>
            </div>
            </div>
        </div>


        <x-flash-message status="session('status')"/>


        <form method="get" action="{{ route('hinban.hinban_index2')}}" class="mt-4">
            <div class="lg:flex">
                <div class="md:flex">
                    <label for="year_code" class="items-center text-sm mt-2" >年CD：</label>
                    <select class="w-24 h-8 text-sm pt-1 mr-2 mb-2 rounded" id="year_code" name="year_code" type="number" class="border">
                    <option value="" @if(\Request::get('year_code') == '999') selected @endif >指定なし</option>
                    @foreach ($years as $year)
                        <option value="{{ $year->year_code }}" @if(\Request::get('year_code') == $year->year_code ) selected @endif >{{ $year->year_code  }}</option>
                    @endforeach
                    </select>
                    <label for="brand_code" class="items-center text-sm mt-2 " >Brand：</label>
                    <select class="w-24 h-8 text-sm pt-1 border mb-2 mr-4 rounded" id="brand_code" name="brand_code" type="number" >
                    <option value="" @if(\Request::get('brand_code') == '0') selected @endif >指定なし</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->id  }}={{ $brand->brand_name  }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="flex">
                    <div>
                        <label for="unit_code" class="items-center text-sm mt-2 mr-2" >Unit：</label>
                        <select class="w-24 h-8 text-sm pt-1 mr-4 mb-2 border rounded" id="unit_code" name="unit_code" type="number" >
                        <option value="" @if(\Request::get('unit_code') == '0') selected @endif >指定なし</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->unit_id }}" @if(\Request::get('unit_code') == $unit->unit_id ) selected @endif >{{ $unit->unit_id  }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="face_code" class="items-center text-sm mt-2 " >Face：</label>
                        <select class="w-24 h-8 text-sm pt-1 mr-4 mb-2 border rounded" id="face_code" name="face_code" >
                        <option value="" @if(\Request::get('face_code') == '0') selected @endif >指定なし</option>
                        @foreach ($faces as $face)
                            <option value="{{ $face->face_code }}" @if(\Request::get('face_code') == $face->face_code ) selected @endif >{{ $face->face_code  }}={{ $face->face_item  }}</option>
                        @endforeach
                        </select>
                    </div>

                <div>
                    <button type="button" class="w-20 h-8 bg-blue-300 text-white ml-2 hover:bg-indigo-600 rounded " onclick="location.href='{{ route('hinban.hinban_index2') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">全表示</button>
                </div>
                </div>
                <div class="md:ml-4">
                    <label for="order" class="mr-1 leading-7 text-sm  text-gray-800 ">並順：</label>
                    <select id="order" name="order" class="w-24 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
                        {{-- <option value="" @if(\Request::get('order') == '0' ) selected @endif >選択</option> --}}
                        <option value="u" @if(\Request::get('order') == "u" || \Request::get('order') == '0') selected @endif >新着順</option>
                        <option value="h" @if(\Request::get('order') == "h") selected @endif>品番順</option>
                    </select>
                </div>
            </div>
        </form>

    </x-slot>

    <div class="py-6 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-2/3 bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">年</th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">BR</th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">UNIT</th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Face</th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品番</th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品名</th>
                        <th class="w-1/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ｺﾒﾝﾄ数</th>
                        @if(Auth::user()->role_id <= 3)
                        <th class="w-3/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">削除</th>
                        @endif
                        {{-- <th class="w-3/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">元売価</th> --}}
                        {{-- <th class="w-3/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">現売価</th> --}}
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td class="w-1/15 md:px-4 py-1"> {{ $product->year_code }} </td>
                        <td class="w-1/15 md:px-4 py-1">{{ $product->brand_name }}</td>
                        <td class="w-1/15 md:px-4 py-1"> {{ $product->unit_id }}</td>
                        <td class="w-1/15 md:px-4 py-1"> {{ $product->face_code }}</td>
                        {{-- <td class="w-2/15 md:px-4 py-1"> {{ $product->hinban_id }}</td> --}}
                        <td class="w-2/15 md:px-4 py-1"> <a href="{{ route('hinban_show',['id'=>$product->hinban_id]) }}" class=" text-indigo-600 ml-2 rounded"  >{{ $product->hinban_id }}</a></td>
                        <td class="w-4/15 md:px-4 py-1 text-left">{{ $product->hinban_name }}</td>
                        <td class="w-1/15 md:px-4 py-1 text-center">{{ $product->comment_count }}</td>
                        @if(Auth::user()->role_id <= 3)
                        <td class="w-1/15 md:px-4 py-1 text-center">
                            <form action="{{ route('hinban_destroy_one', ['id'=>$product->hinban_id]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-16 text-sm border rounded bg-red-500 text-white hover:bg-red-600">削除</button>
                            </form>
                        </td>
                    @endif
                        {{-- <td class="w-1/15 md:px-4 py-1 text-right">{{ number_format($product->local_cur_price )}}</td> --}}
                        {{-- <td class="w-1/15 md:px-4 py-1 text-right">{{ number_format($product->local_yen_price )}}</td> --}}

                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{  $products->links()}}
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

        const face = document.getElementById('face_code')
        face.addEventListener('change', function(){
        this.form.submit()
        })

        const order = document.getElementById('order')
        order.addEventListener('change', function(){
        this.form.submit()
        })

    </script>

</x-app-layout>
