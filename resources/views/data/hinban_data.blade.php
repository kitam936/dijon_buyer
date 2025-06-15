
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <div class="flex">
            <div>
            品番データ
            </div>
            <div class="w-40 ml-60 text-sm items-right mb-0">
                <button onclick="location.href='{{ route('data.data_index') }}'" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">戻る</button>
            </div>
            </div>
        </h2>

        <x-flash-message status="session('status')"/>

        <form method="get" action="{{ route('data.hinban_index')}}" class="mt-4">
            <div class="lg:flex">
                <div class="md:flex">
                    <label for="year_code" class="items-center text-sm mt-2" >年度CD：</label>
                    <select class="w-24 h-8 text-sm pt-1 mr-2 mb-2" id="year_code" name="year_code" type="number" class="border">
                    <option value="" @if(\Request::get('year_code') == '999') selected @endif >指定なし</option>
                    @foreach ($years as $year)
                        <option value="{{ $year->year_code }}" @if(\Request::get('year_code') == $year->year_code ) selected @endif >{{ $year->year_code  }}</option>
                    @endforeach
                    </select>
                    <label for="brand_code" class="items-center text-sm mt-2 " >Brand：</label>
                    <select class="w-24 h-8 text-sm pt-1 border mb-2 mr-4 " id="brand_code" name="brand_code" type="number" >
                    <option value="" @if(\Request::get('brand_code') == '0') selected @endif >指定なし</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @if(\Request::get('brand_code') == $brand->id ) selected @endif >{{ $brand->id  }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="flex">
                    <label for="unit_code" class="items-center text-sm mt-2 " >Unit：</label>
                    <select class="w-24 h-8 text-sm pt-1 mr-4 mb-2 border " id="unit_code" name="unit_code" type="number" >
                    <option value="" @if(\Request::get('unit_code') == '0') selected @endif >指定なし</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" @if(\Request::get('unit_code') == $unit->id ) selected @endif >{{ $unit->id  }}</option>
                    @endforeach
                    </select>


                <div>
                    <button type="button" class="w-20 h-8 bg-indigo-500 text-white ml-2 hover:bg-indigo-600 rounded " onclick="location.href='{{ route('data.hinban_index') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">全表示</button>
                </div>
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
                        {{-- <th class="w-3/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">元売価</th> --}}
                        {{-- <th class="w-3/15 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">現売価</th> --}}
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td class="w-1/15 md:px-4 py-1"> {{ $product->year_code }} </td>
                        <td class="w-1/15 md:px-4 py-1">{{ $product->brand_id }}</td>
                        <td class="w-1/15 md:px-4 py-1"> {{ $product->unit_id }}</td>
                        <td class="w-1/15 md:px-4 py-1"> {{ $product->face_code }}</td>
                        <td class="w-2/15 md:px-4 py-1"> {{ $product->hinban_id }}                            </td>
                        <td class="w-4/15 md:px-4 py-1 text-left">{{ $product->hinban_name }}</td>
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

        const hinban = document.getElementById('hinban_code')
        hinban.addEventListener('change', function(){
        this.form.submit()
        })



    </script>

</x-app-layout>
