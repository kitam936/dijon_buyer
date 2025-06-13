<x-app-layout>
    <x-slot name="header">

        <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-200 leading-tight">
        <div>
            商品詳細
        </div>
        </h2>

        <div><input type="hidden" name="hin_id" value="{{ $product->hinban_id }}" ></div>

        <div class="flex ml-8 ">
        <div class="ml-2 mb-2 md:mb-0">
            <button type="button" onclick="location.href='{{ route('hinban.hinban_index2') }}'" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">登録済品番データ</button>
        </div>
        {{-- <div class="ml-4 mb-0">
            <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('sales_product') }}'" >商品別売上</button>
        </div> --}}
        </div>


    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 bg-white border-b border-gray-200">

                        <div class="-m-2">
                            <div class="p-2 ">
                                <div class="p-2 w-full ">
                                    <div class="flex">
                                    <div class="relative">
                                        <label for="brand_id" class="mr-2 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Brand</label>
                                        <div  id="brand_id" name="brand_id" value="{{$product->brand_id}}" class="mr-2 h-8 text-sm w-16 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700  px-3 leading-8 transition-colors duration-200 ease-in-out">{{$product->brand_id}}</div>
                                    </div>

                                    <div class="relative">
                                        <label for="hinban" class="mr-2 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">品番</label>
                                        <div  id="hinban" name="hinban" value="{{$product->hinban_id}}" class="mr-2 h-8 text-sm w-32 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700  px-3 leading-8 transition-colors duration-200 ease-in-out">{{$product->hinban_id}}</div>
                                    </div>
                                    <div class="relative">
                                        <label for="year_code" class="mr-2 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Unit</label>
                                        <div  id="year_code" name="year_code" value="{{$product->year_code}}" class="mr-2 h-8 text-sm w-16 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700  px-3 leading-8 transition-colors duration-200 ease-in-out">{{$product->year_code}}</div>
                                    </div>
                                    <div class="relative">
                                        <label for="shohin_gun" class="mr-2 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">商品群</label>
                                        <div  id="shohin_gun" name="shohin_gun" value="{{$product->shohin_gun}}" class="mr-2 h-8 text-sm w-16 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700  px-3 leading-8 transition-colors duration-200 ease-in-out">{{$product->shohin_gun}}</div>
                                    </div>
                                    <div class="relative">
                                        <label for="unit_id" class="mr-2 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Unit</label>
                                        <div  id="unit_id" name="unit_id" value="{{$product->unit_id}}" class="mr-2 h-8 text-sm w-16 bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700  px-3 leading-8 transition-colors duration-200 ease-in-out">{{$product->unit_id}}</div>
                                    </div>
                                    </div>

                                    <div class="relative">
                                        <label for="hinban_name" class="mr-2 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">商品名</label>
                                        <div  id="hinban_name" name="hinban_name" value="{{$product->hinban_name}}" class="mr-2 h-8 text-sm w-full bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700  px-3 leading-8 transition-colors duration-200 ease-in-out">{{$product->hinban_name}}</div>
                                    </div>

                                    <div class="relative">
                                        <label for="mix_rate" class="mr-2 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">混率</label>
                                        <div  id="mix_rate" name="mix_rate" value="{{$product->mix_rate}}" class="mr-2 h-8 text-sm w-full bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700  px-3 leading-8 transition-colors duration-200 ease-in-out">{{$product->mix_rate}}</div>
                                    </div>

                                    <div class="flex">

                                    </div>
                                    </div>

                                    <div class="relative ml-2 mr-2">
                                        <label for="hinban_info" class="ml-1 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">商品情報</label>
                                        <div id="hinban_info" name="hinban_info" rows="10" class="mr-2 h-8 text-sm w-full bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700  px-3 leading-8 transition-colors duration-200 ease-in-out">{!! nl2br(e($product->hinban_info)) !!}</div>
                                    </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-0 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">


            <table class="md:w-full bg-white table-auto w-full text-center whitespace-no-wrap">
                <thead>
                    <tr>
                        <th class="w-1/12 md:2/12 md:px-4  title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">seq</th>
                        <th class="w-1/12 md:2/12 md:px-4  title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ck</th>
                        <th class="w-2/12 md:2/12 md:px-4  title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">品番</th>
                        <th class="w-2/12 md:2/12 md:px-4  title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Col</th>
                        <th class="w-2/12 md:2/12 md:px-4  title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Size</th>
                        <th class="w-2/12 md:2/12 md:px-4  title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">現地ｺｽﾄ</th>
                        {{-- <th class="w-2/12 md:2/12 md:px-4  title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">円建ｺｽﾄ</th> --}}
                        <th class="w-2/12 md:2/12 md:px-4  title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">想定円ｺｽﾄ</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($skus as $sku)
                    <tr>
                    <td class="w-1/12 md:2/12 text-sm md:px-4  text-center">{{ $sku->seq }} </td>
                    @if($sku->sku_code)
                    <td class="w-1/12 md:2/12 text-sm md:px-4 text-green-500 text-center">OK</td>
                    @else
                    <td class="w-1/12 md:2/12 text-sm md:px-4 text-red-500 text-center"></td>
                    @endif
                    <td class="w-2/12 md:2/12 text-sm md:px-4  text-center">{{ $sku->hinban_id }}</td>
                    <td class="w-2/12 md:2/12 text-sm md:px-4  text-center">{{ $sku->col_id }}</td>
                    <td class="w-2/12 md:2/12 text-xs md:px-4  text-center">{{ $sku->size_id }}</td>
                    <td class="w-2/12 md:2/12 text-sm md:px-4  text-center">{{ $sku->local_cur_price }}</td>
                    {{-- <td class="w-2/12 md:2/12 text-sm md:px-4  text-center">{{ $sku->local_cur_price * $ex_rate->ex_rate /100}}</td> --}}
                    <td class="w-2/12 md:2/12 text-sm md:px-4  text-center">{{ $sku->local_cur_price * $ex_rate->ex_rate /100 * $cost_rate->cost_rate/100}}</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


</x-app-layout>
