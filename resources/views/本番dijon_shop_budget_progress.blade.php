
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            社店予算進捗<br>
        </h2>
        <div class="flex">
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
            </div>
            <div class="pl-2 mt-2  ml-8 ">
                <button type="button" class="w-32 text-center text-sm text-white bg-blue-500 border-0 py-1 px-2 focus:outline-none hover:bg-blue-700 rounded " onclick="location.href='{{ route('budget_progress_reset') }}'" >選択リセット</button>
            </div>
        </div>

        {{-- <span class="items-center text-sm mt-2 text-gray-800 dark:text-gray-200 leading-tight" >　※Brand・店舗を選択してください　　　</span> --}}

        <form method="get" action="{{ route('budget_progress')}}" class="mt-4">
        <div >
                <div>
                    <label for="type2" class="mr-5 leading-7 text-sm  text-gray-800 ">集計期間単位を年月・月・週・日から選択</label>
                    <select id="type2" name="type2" class="w-28 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
                        <option value="d" @if(\Request::get('type2') == '0' || \Request::get('type2') == "d") selected @endif >日別</option>
                        {{-- <option value="dry">dry</option> --}}
                        {{-- <option value="d" @if(\Request::get('type2') == "d") selected @endif>日別</option> --}}
                        <option value="w" @if(\Request::get('type2') == "w") selected @endif>週別</option>
                        <option value="m" @if(\Request::get('type2') == "m") selected @endif>月別</option>
                        <option value="y" @if(\Request::get('type2') == "y") selected @endif>年度別</option>
                        {{-- <option value="wet">wet</option> --}}
                    </select>
                </div>
        </div>
        <div class="flex">
            <div class="flex mb-2">
                <div class="flex">
                    <div>
                    <label for="YM1" class="mr-2 leading-7 text-sm  text-gray-800 ">期間(年月)</label>
                    <select class="w-36 h-8 rounded text-sm pt-1" id="YM1" name="YM1" type="number" class="border">
                        <option value="{{ $max_YM }}" @if(\Request::get('YM1') == '0') selected @endif >年月選択(from)</option>
                        @foreach ($YMs as $YM)
                            <option value="{{ $YM->YM }}" @if(\Request::get('YM1') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <span class="items-center text-sm mt-4 md:mt-2 text-gray-800 leading-tight" >　　～</span>
                <div class="mt-00 md:mt-0">
                    <label for="YM1" class="mr-0 leading-7 text-sm  text-gray-800 ">　</label>
                    <select class="w-36 h-8 rounded text-sm pt-1" id="YM2" name="YM2" type="number" class="border">
                    <option value="{{ $max_YM }}" @if(\Request::get('YM2' ) == '0') selected @endif >年月選択(to)</option>
                    @foreach ($YMs as $YM)
                        <option value="{{ $YM->YM }}" @if(\Request::get('YM2') == $YM->YM) selected @endif >{{ floor(($YM->YM)/100)%100 }}年{{ ($YM->YM)%100 }}月</option>
                    @endforeach
                    </select>
                </div>
                <span class="items-center text-sm mt-2" >　</span>
            </div>
        </div>

        <div class="md:flex">
            <div class="flex">
                <div>
                    <label for="type1" class="mr-5 leading-7 text-sm  text-gray-800 ">社店種別</label>
                    <select id="type1" name="type1" class="w-28 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
                        <option value="" @if(\Request::get('type1') == '0') selected @endif >全社店</option>
                        {{-- <option value="dry">dry</option> --}}
                        <option value="co" @if(\Request::get('type1') == "co") selected @endif>社別</option>
                        <option value="sh" @if(\Request::get('type1') == "sh") selected @endif>店別</option>
                        {{-- <option value="wet">wet</option> --}}
                    </select>
                </div>
                <div>
                    <label for="area_id" class="mr-3 leading-7 text-sm  text-gray-800 ">エリア指定</label>
                    <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-1 " id="area_id" name="area_id" >
                    <option value="" @if(\Request::get('area_id') == '0') selected @endif >選択なし</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" @if(\Request::get('area_id') == $area->id ) selected @endif >{{ $area->area_name  }}</option>
                    @endforeach
                    </select>
                </div>

            </div>


            <div class="flex md:ml-4">
                {{--  @if(\Request::get('type1') == 'co' || \Request::get('type1') == 'sh')  --}}
                <div>
                    <label for="co_id" class="mr-6 leading-7 text-sm  text-gray-800 ">社を指定</label>
                    <select class="w-28 h-8 rounded text-sm pt-1 border mb-2 mr-6 " id="co_id" name="co_id" >
                    <option value="" @if(\Request::get('co_id') == '0') selected @endif >選択なし</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" @if(\Request::get('co_id') == $company->id ) selected @endif >{{ $company->co_name  }}</option>
                    @endforeach
                    </select>
                </div>
                {{--  @endif  --}}
                {{--  @if(\Request::get('type1') == 'sh')  --}}
                <div>
                    <label for="sh_id" class="mr-5 leading-7 text-sm  text-gray-800 ">店を指定</label>
                    <select class="w-32 h-8 rounded border text-sm items-center pt-1" id="sh_id" name="sh_id" >
                        <option value="" @if(\Request::get('sh_id') == '0') selected @endif >選択なし</option>
                        @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}" @if(\Request::get('sh_id') == $shop->id ) selected @endif >{{ $shop->shop_name  }}</option>
                        @endforeach
                    </select>
                </div>
                {{--  @endif  --}}
            </div>
        </div>


        {{-- <div class="ml-2 md:ml-4">
            <button type="button" class="w-20 h-8 bg-indigo-500 text-white ml-2 hover:bg-indigo-600 rounded" onclick="location.href='{{ route('sales_transition') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">全表示</button>
        </div> --}}
        </form>



        <div class="ml-0 mt-3 py-0 md:w-2/3 border">
            <div class="md:flex w-full  sm:px-0 lg:px-0 border mt-0 ml-0">
                <div class='md:w-2/3 pl-0 border bg-gray-100 h-6 text-sm'>
                    　予算累計：{{ number_format(round($bg_total->total)/1000) }}千　
                    実績累計：{{ number_format(round($total->total)/1000)}}千　
                </div>
                <div class='md:w-2/3 pl-0 border bg-gray-100 h-6 text-sm'>
                    @if($bg_total->total>0)
                    　予算比　：{{ number_format(($total->total/$bg_total->total)*100)}}％
                    @endif
                </div>
            </div>
        </div>



    </x-slot>

        <div class="py-6 border">
        <div class="md:w-2/3 sm:px-4 lg:px-4 border">
            <table class="mx-auto table-auto bg-white w-full text-center whitespace-no-wrap">
                <thead >
                <tr>
                    <th class="w-3/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">年度・月・週・日</th>
                    <th class="w-3/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">予算(千)</th>
                    <th class="w-3/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">実績(千)</th>
                    <th class="w-3/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">予算比</th>
                </tr>
                </thead>

                <tbody>
                    @foreach ($merged_data as $data)
                <tr>
                    <td class="w-3/12 md:px-4 py-1 text-sm">{{ $data->date }}</td>

                    <td class="w-3/12 pr-4 md:px-8 py-1 text-sm text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($data->bg_total)/1000)}}</span></td>
                    <td class="w-3/12 pr-4 md:px-8 py-1 text-sm text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(round($data->total)/1000)}}</span></td>
                    @if($data->bg_total>0)
                    <td class="w-3/12 pr-8 md:px-8 py-1 text-sm text-right"><span style="font-variant-numeric:tabular-nums"> {{ number_format(($data->total/$data->bg_total)*100)}} %</span></td>
                    @else
                    <td class="w-3/12 pr-8 md:px-8 py-1 text-sm text-right"><span style="font-variant-numeric:tabular-nums"> --</span></td>
                    @endif
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>


<script>

const type2 = document.getElementById('type2')
type2.addEventListener('change', function(){
this.form.submit()
    })

const type1 = document.getElementById('type1')
type1.addEventListener('change', function(){
this.form.submit()
    })

const YM1 = document.getElementById('YM1')
YM1.addEventListener('change', function(){
this.form.submit()
    })

const YM2 = document.getElementById('YM2')
YM2.addEventListener('change', function(){
this.form.submit()
})

const area = document.getElementById('area_id')
area.addEventListener('change', function(){
this.form.submit()
})

const company = document.getElementById('co_id')
company.addEventListener('change', function(){
this.form.submit()
})

const shop = document.getElementById('sh_id')
shop.addEventListener('change', function(){
this.form.submit()
})

</script>

</x-app-layout>



