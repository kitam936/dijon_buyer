<x-app-layout>
    <h2 class="font-semibold text-xl mt-4 ml-4 mb-4 text-gray-800 leading-tight"> 商品移動入力状況</h2>

    <x-flash-message status="session('status')" />

    <div class="ml-4 flex mb-4 md:mb-0 md:ml-4">
        <div class="ml-4 mt-2 md:mt-0 md:ml-8">
            <button type="button" class="w-24 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('analysis_index') }}'" >Menu</button>
        </div>
        <div class="ml-4 mt-2 md:ml-4 md:mt-0">
            <button type="button" class="w-24 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('move_result_index') }}'" >Dataリスト</button>
        </div>
        <div class="ml-4 mt-2 md:ml-4 md:mt-0">
            <button type="button" class="w-24 text-center text-sm text-white bg-green-500 bpos-0 py-1 px-2 focus:outline-none hover:bg-green-600 rounded " onclick="location.href='{{ route('move_scan') }}'" >SCAN開始</button>
        </div>
    </div>

    <div  class="mt-2 ml-12 h-6 text-sm w-60 bg-gray-100 rounded bg-opacity-50 focus:border-indigo-500 border focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-6 transition-colors duration-200 ease-in-out"> 作業者：{{ Auth::user()->name }}
    </div>

    @if($h_exist || $s_exist)
    <div class="ml-12 text-ml text-red-500">
        マスターに存在しない商品コードがあります<br>
        赤く表示された行を削除した上で<br>
        再度スキャンしてください。<br><br>
        読み込めない場合は手入力で<br>
        品番（6桁）カラー（2桁）サイズ（2桁）<br>
        の10桁数字を打ち込んで追加してください<br><br>
        それでもエラーが出る場合は一旦商品を隔離して<br>
        追って担当者に確認してください。
    </div>
    @else
    <div class="ml-8 md:ml-2">
    <form method="POST" action="{{ route('move_complete') }}" class="mt-2">
        @csrf

        @if(Auth::User()->shop_id >1000 && (Auth::User()->shop_id != 4102))
        <div>
            <label for="to_shop_id" class="mr-5 leading-7 text-sm  text-gray-800 ">着店指定</label>
            <select class="w-32 h-8 rounded border text-sm items-center pt-1" id="to_shop_id" name="to_shop_id" >
                <option value="106" @if(\Request::get('to_shop_id') == '0') selected @endif >神田運送</option>
                @foreach ($shops as $shop)
                <option value="{{ $shop->id }}" @if(\Request::get('to_shop_id') == $shop->id ) selected @endif >{{ $shop->shop_name  }}</option>
                @endforeach
            </select>
        </div>
        @endif
        @if(Auth::User()->shop_id == 101 || (Auth::User()->shop_id >4000 && Auth::User()->shop_id <5000 ))
        <label for="to_shop_id" class="mr-5 leading-7 text-sm  text-gray-800 ">着店指定</label>
            <select class="w-32 h-8 rounded border text-sm items-center pt-1" id="to_shop_id" name="to_shop_id" >
                <option value="106" @if(\Request::get('to_shop_id') == '0') selected @endif >神田運送</option>

            </select>
        @endif
        <label>メモ：<input type="text" name="memo" style="width:300px;" class="rounded w-50" ></label>
        <button type="submit" class="mt-4 ml-0 md:ml-4 w-24 text-center text-sm text-white bg-pink-500 bpos-0 py-1 px-2 focus:outline-none hover:bg-pink-700 rounded " onclick="return confirm('Dataを確定してよろしいですか？')">確定</button>
    </form>
    </div>
    @endif


    <form method="get" action="{{ route('move_index')}}" class="mt-4">
    <div class="flex ml-8">
    <div>
        <label for="order" class="mr-1 leading-7 text-sm  text-gray-800 ">並び順：</label>
        <select id="order" name="order" class="w-24 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
            <option value="" @if(\Request::get('order') == '0' ) selected @endif >選択</option>
            <option value="u" @if(\Request::get('order') == "u") selected @endif >新着順</option>
            <option value="h" @if(\Request::get('order') == "h") selected @endif>品番順</option>
        </select>
    </div>

    <div>
        <label for="ng" class="mr-1 leading-7 text-sm  text-gray-800 ">表示</label>
        <select id="ng" name="ng" class="w-24 h-8 rounded text-sm pt-1 border mr-6 mb-2" type="text">
            <option value="" @if(\Request::get('type') == '0' ) selected @endif >選択</option>
            <option value="e" @if(\Request::get('type') == "e") selected @endif >エラーのみ</option>
            <option value="a" @if(\Request::get('type') == "a") selected @endif>全商品</option>

        </select>
    </div>
    </div>
    </form>

<div class="mt-4">
    <h3 class="flex ml-4 font-semibold text-ml text-indigo-700 leading-tight mt-4">
        {{-- 明細 --}}

        <div class="flex relative ml-4">
            <label for="total_pcs" class="leading-7 text-ml text-indigo-700 ">数</label>
            @if($works_total)
            <div  id="total_pcs" name="total_pcs" value="{{$works_total->pcs}}" class="h-8 text-ml w-24 text-indigo-700 bg-gray-100 bg-opacity-50 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out">{{$works_total->pcs}}枚</div>
            @else
            <div  id="total_pcs" name="total_pcs" class="h-8 text-ml w-24 bg-gray-100 bg-opacity-50 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 px-3 leading-8 transition-colors duration-200 ease-in-out"> 0 枚 </div>
            @endif
        </div>
    </h3>



    <div class="mt-0">
        <table class="ml-1 bg-white text-left table-auto whitespace-no-wrap">

            <thead>
                <tr>
                    {{-- <th class="w-3/16 md:px-0 py-1 min-w-[100px]">CK</th> --}}
                    <th class="w-3/16 pr-8 md:pr-0 py-1 text-left">　　品番-色-サイズ　　　　売価</th>


                </tr>
            </thead>
            <tbody>
                @foreach ($works as $work )
                <tr>
                    <td class="w-5/16 md:px-2 py-1" >
                        <form method="POST" action="{{ route('move_update', $work->id) }}">
                            @csrf
                            <div class="flex ml-1">
                                @if(is_null($work->sku_ck))
                                <input type="hidden" value="{{ ($work->id) }}" class="rounded mr-2 bg-red-300 text-ml" style="width:120px; font-variant-numeric:tabular-nums">
                                <input disabled value="{{ substr($work->raw_cd,0,6) }}-{{ substr($work->raw_cd,6,2)}}-{{ substr($work->raw_cd,8,2) }}" class="rounded mr-2 bg-red-300 text-ml" style="width:150px; font-variant-numeric:tabular-nums">
                                @else
                                <input type="hidden" value="{{ ($work->id) }}" class="rounded mr-2 bg-red-300 text-ml" style="width:120px; font-variant-numeric:tabular-nums">
                                <input disabled value="{{ substr($work->raw_cd,0,6) }}-{{ substr($work->raw_cd,6,2)}}-{{ substr($work->raw_cd,8,2) }}" class="rounded mr-2 bg-gray-200 text-ml " style="width:150px; font-variant-numeric:tabular-nums">
                                @endif
                                <input type="number" name="move_price2" value="{{ $work->move_price }}"  class="rounded mr-2 text-ml " style="width:80px; font-variant-numeric:tabular-nums">
                                <input type="number" name="pcs" value="{{ $work->pcs }}" class="rounded mr-2" style="width:60px; font-variant-numeric:tabular-nums">
                                <button type="submit" class="w-12 h-9 mt-1 items-right bg-blue-500 text-sm text-white ml-1 hover:bg-blue-600 rounded ">更新</button>
                            </div>
                        </form>
                    </td>
                    <td class="w-2/16 md:px-2 py-1">
                        <div>
                        <form method="POST" action="{{ route('move_destroy', $work->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-12 h-9 mt-1 items-center bg-red-500 text-sm text-white ml-0 hover:bg-red-600 rounded " onclick="return confirm('削除しますか？')">削除</button>
                        </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    {{  $works->appends([
        'order'=>\Request::get('order'),
        'ng'=>\Request::get('ng'),
    ])->links()}}
    </div>



    {{-- {{ $works->links()}} --}}
</div>

<script>

const order = document.getElementById('order')
    order.addEventListener('change', function(){
    this.form.submit()
    })

    const ng = document.getElementById('ng')
    ng.addEventListener('change', function(){
    this.form.submit()
    })

</script>
</x-app-layout>
