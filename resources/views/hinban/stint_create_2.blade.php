<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Stint登録
        </h2>

        <div class="flex mt-4">
        <div class="ml-2 md:ml-4">
            <button type="button" class="w-32 h-8 bg-indigo-500 text-white md:ml-60 hover:bg-indigo-600 rounded" onclick="location.href='{{ route('my_stint') }}'" >MyStint</button>
        </div>
        <div class="ml-12 md:ml-12">
            <button type="button" class="w-32 h-8 bg-green-500 text-white hover:bg-green-600 rounded" onclick="location.href='{{ route('stint_create') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-green-300 rounded ">Dataクリア</button>
        </div>
        </div>

    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white text-indigo-600 overflow-hidden shadow-xl sm:rounded-lg">

            <div CLASS="max-w-2xl mx-auto">

                <x-validation-errors class="mb-4" />

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            {{-- <x-flash-message status="session('status')"/> --}}

            <form method="POST" action="{{ route('stint_store') }}" enctype="multipart/form-data">
                @csrf

                <div>
                    <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}" />
                </div>


                <div class="flex ml-2 mt-1">
                    <div class="flex justify-between">
                        <div class="mr-2">
                            <x-label for="start_date" value="走行日" />
                            <x-input id="start_date" class="w-40 bg-gray-100 block mt-1 " id="start_date" type="text" name="start_date" :value="old('start_date')" required  />
                        </div>
                        <div>
                            <x-label for="start_time" value="開始時間" />
                            <x-input id="start_time" class="w-40 bg-gray-100 block mt-1 " id="start_time" type="text" name="start_time" :value="old('start_time')" required  />
                        </div>
                    </div>
                </div>
                <div class="flex relative w-40">
                    <div class="relative ml-2 mr-0">
                    <x-label for="cir_id" value="サーキット" />
                    <select  id="cir_id" name="cir_id"  class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                        <option value="{{ $latest->cir_id }}" @if(\Request::get('cir_id') == '0') selected @endif >{{ $latest->cir_name }}</option>
                        {{-- <option value="{{ $user->area_id }}" @if(\Request::get('area_id') == '0') selected @endif >{{ $user->area_name }}</option> --}}
                        @foreach ($cirs as $cir)
                            <option value="{{ $cir->id }}" @if(old('cir_id') == $cir->id) selected @endif>{{ $cir->cir_name }}</option>
                            {{-- <option value="{{ $cir->id }}"  >{{ $cir->cir_name }}</option> --}}
                        @endforeach
                    </select>
                    </div>
                    <div class="relative ml-2">
                        <x-label for="kart_id" value="カート" />
                        <select  id="kart_id" name="kart_id"  class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                        <option value="{{ $latest->my_kart_id }}" @if(\Request::get('kart_id') == '0') selected @endif >{{$latest->my_kart_id }}_{{ $latest->maker_name }}_{{ $latest->model_year }}</option>
                        {{-- <option value="{{ $user->area_id }}" @if(\Request::get('area_id') == '0') selected @endif >{{ $user->area_name }}</option> --}}
                        @foreach ($karts as $kart)
                        <option value="{{ $kart->mykart_id }}" @if(old('kart_id') == $kart->mykart_id) selected @endif>{{ $kart->mykart_id }}_{{ $kart->maker_name }}_{{ $kart->model_year }}</option>
                            {{-- <option value="{{ $kart->id }}"  >{{ $kart->id }}_{{ $kart->maker_name }}_{{ $kart->model_year }}</option> --}}
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex mt-1">

                    <div class="relative ml-2 mr-0">
                        <x-label for="engine_id" value="エンジン" />
                        <select  id="engine_id" name="engine_id"  class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                        <option value="{{ $latest->my_engine_id }}" @if(\Request::get('engine_id') == '0') selected @endif >{{ $latest->my_engine_id }}_{{ $latest->engine_name }}_{{ \Carbon\Carbon::parse($latest->my_engine_date)->format('y-m') }}</option>
                        {{-- <option value="{{ $user->area_id }}" @if(\Request::get('area_id') == '0') selected @endif >{{ $user->area_name }}</option> --}}
                        @foreach ($engines as $engine)
                        <option value="{{ $engine->myengine_id }}" @if(old('engine_id') == $engine->myengine_id) selected @endif>{{ $engine->myengine_id }}_{{ $engine->engine_name }}_{{ \Carbon\Carbon::parse($engine->purchase_date)->format('y-m') }}</option>
                            {{-- <option value="{{ $engine->id }}"  >{{ $engine->id }}_{{ $engine->engine_name }}_{{ \Carbon\Carbon::parse($engine->purchase_date)->format('y-m') }}</option> --}}
                        @endforeach
                        </select>
                    </div>
                    <div class="relative ml-2 ">
                        <x-label for="tire_id" value="タイヤ" />
                        <select  id="tire_id" name="tire_id"  class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                        <option value="{{ $latest->my_tire_id }}" @if(\Request::get('tire_id') == '0') selected @endif >{{ $latest->my_tire_id }}_{{ $latest->tire_name }}_{{ \Carbon\Carbon::parse($latest->my_tire_date)->format('y-m') }}</option>
                        {{-- <option value="{{ $user->area_id }}" @if(\Request::get('area_id') == '0') selected @endif >{{ $user->area_name }}</option> --}}
                        @foreach ($tires as $tire)
                        <option value="{{ $tire->mytire_id }}" @if(old('tire_id') == $tire->mytire_id) selected @endif>{{ $tire->mytire_id }}_{{ $tire->tire_name }}_{{ \Carbon\Carbon::parse($tire->purchase_date)->format('y-m') }}</option>
                            {{-- <option value="{{ $tire->id }}"  >{{ $tire->id }}_{{ $tire->tire_name }}_{{ \Carbon\Carbon::parse($tire->purchase_date)->format('y-m') }}</option> --}}
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="ml-2 mr-4 flex relative ">
                    <div >
                        <x-label for="atm_pressure" value="気圧" class="mt-0"/>
                        <select name="atm_pressure" class="w-24 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="{{ $latest->atm_pressure }}" @if(\Request::get('atm_pressure') == '0') selected @endif >{{ $latest->atm_pressure }}</option>
                            @for($i = 990; $i <= 1040; $i=$i+5)
                            <option value="{{ $i }}" @if(old('atm_pressure') == $i) selected @endif>{{ $i }}</option>
                            {{-- <option value="{{$i}}">{{$i}}</option> --}}
                            @endfor
                        </select>
                    </div>

                    <div>
                        <x-label for="temp_id" value="気温" class="mt-0 ml-2"/>
                        <select class="w-24 bg-gray-100 border-gray-300 ml-2 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mr-2 " id="temp_id" name="temp_id" type="text" >
                            <option value="{{ $latest->temp }}" @if(\Request::get('temp_id') == '0') selected @endif >{{ $latest->temp_range }}</option>
                            @foreach ($temps as $temp)
                            <option value="{{ $temp->id}}" @if(old('temp_id') == $temp->id) selected @endif>{{ $temp->temp_range  }}</option>
                                {{-- <option value="{{ $temp->id }}" @if(\Request::get('temp_id') == $temp->id ) selected @endif >{{ $temp->temp_range  }}</option> --}}
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-label for="humi_id" value="湿度" class="mt-0"/>
                        <select class="w-24 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mr-2 " id="humi_id" name="humi_id" type="text" >
                            <option value="{{ $latest->humidity }}" @if(\Request::get('humi_id') == '0') selected @endif >{{ $latest->humi_range  }}</option>
                            @foreach ($humis as $humi)
                            <option value="{{ $humi->id}}" @if(old('humi_id') == $humi->id) selected @endif>{{ $humi->humi_range  }}</option>
                                {{-- <option value="{{ $humi->id }}" @if(\Request::get('humi_id') == $humi->id ) selected @endif >{{ $humi->humi_range  }}</option> --}}
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="ml-2 mr-4 flex">
                    <div class="mr-2">
                        <x-label for="dry/wet" value="路面" class="mt-0"/>
                        <select name="dry/wet" class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="{{ $latest->dry_wet }}" @if(\Request::get('dry/wet') == '0') selected @endif >{{ $latest->dry_wet }}</option>
                            {{-- <option value="dry">dry</option>
                            <option value="wet">wet</option> --}}
                            <option value="dry" @if(old('dry/wet') == "dry") selected @endif>dry</option>
                            <option value="wet" @if(old('dry/wet') == "wet") selected @endif>wet</option>
                        </select>
                    </div>

                    <div>
                        <x-label for="road_temp_id" value="路面温度" class="mt-0"/>
                        <select class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mr-2 " id="road_temp_id" name="road_temp_id" type="text" >
                            <option value="{{ $latest->road_temp }}" @if(\Request::get('road_temp_id') == '0') selected @endif >{{ $latest->roadtemp_range }}</option>
                            @foreach ($road_temps as $road_temp)
                            <option value="{{ $road_temp->id}}" @if(old('road_temp_id') == $road_temp->id) selected @endif>{{ $road_temp->roadtemp_range  }}</option>
                                {{-- <option value="{{ $road_temp->id }}" @if(\Request::get('road_temp_id') == $road_temp->id ) selected @endif >{{ $road_temp->roadtemp_range  }}</option> --}}
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="ml-2 mr-4 flex">
                    <div class="mr-2">
                        <x-label for="tire_pres" value="タイヤ圧" class="mt-0"/>
                        <select name="tire_pres" class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="{{ $latest->tire_pres*100 }}" @if(\Request::get('tire_pres') == '0') selected @endif >{{ $latest->tire_pres }}</option>
                            @for($i = 65; $i <= 130; $i=$i+5)
                            <option value="{{ $i }}" @if(old('tire_pres') == $i) selected @endif>{{ $i/100 }}</option>
                            {{-- <option value="{{$i}}">{{$i/100}}</option> --}}
                            @endfor
                        </select>
                    </div>
                    <div>
                        <x-label for="tire_temp_id" value="タイヤ温度" class="mt-0"/>
                        <select class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mr-2 " id="tire_temp_id" name="tire_temp_id" type="text" >
                            <option value="{{ $latest->tire_temp }}" @if(\Request::get('tire_temp_id') == '0') selected @endif >{{ $latest->tiretemp_range }}</option>
                            @foreach ($tire_temps as $tire_temp)
                            <option value="{{ $tire_temp->id}}" @if(old('tire_temp_id') == $tire_temp->id) selected @endif>{{ $tire_temp->tiretemp_range  }}</option>
                                {{-- <option value="{{ $tire_temp->id }}" @if(\Request::get('tire_temp_id') == $tire_temp->id ) selected @endif >{{ $tire_temp->tiretemp_range  }}</option> --}}
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="ml-2 mr-4 flex">
                <div class="mr-2">
                    <x-label for="fr_tread" value="フロントトレッド" class="mt-0"/>
                    <select name="fr_tread" class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                        <option value="{{ $latest->fr_tread }}" @if(\Request::get('fr_tread') == '0') selected @endif >{{ $latest->fr_tread }}</option>
                        @for($i = 5; $i <= 25; $i=$i+5)
                        <option value="{{ $i }}" @if(old('fr_tread') == $i) selected @endif>{{ $i }}</option>
                        {{-- <option value="{{$i}}">{{$i}}</option> --}}
                        @endfor
                    </select>
                </div>
                <div>
                    <x-label for="re_tread" value="リアトレッド" class="mt-0"/>
                    <select name="re_tread" class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                        <option value="{{ $latest->re_tread }}" @if(\Request::get('re_tread') == '0') selected @endif > {{ $latest->re_tread }}</option>
                        @for($i = 1350; $i <= 1400; $i=$i+5)
                        <option value="{{ $i }}" @if(old('re_tread') == $i) selected @endif>{{ $i }}</option>
                        {{-- <option value="{{$i}}">{{$i}}</option> --}}
                        @endfor
                    </select>
                </div>
                </div>


                <div class="flex ml-2">
                    <div calss="mr-2">
                        <x-label for="upper_of_time" value="BestTime" class="mt-0"/>
                        <select name="upper_of_time" class="w-24 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="" @if(\Request::get('upper_of_time') == '0') selected @endif >秒</option>
                            @for($i = 30; $i <= 60; $i++)
                            <option value="{{ $i }}" @if(old('upper_of_time') == $i) selected @endif>{{ $i }}</option>
                            {{-- <option value="{{$i}}">{{$i}}</option> --}}
                            @endfor
                        </select>
                        <span>.</span>
                    </div>

                    <div class="ml-2">
                        <x-label for="bottom_of_time" value=" コンマ以下2桁" class="mt-0"/>
                        {{-- <x-input id="number_of_sub" class="bg-gray-100 block mt-1 w-full" id="number_of_sub" type="text" name="number_of_sub" :value="old('number_of_sub')" required  /> --}}
                        <select name="bottom_of_time" class="w-24 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="" @if(\Request::get('bottom_of_time') == '0') selected @endif >秒以下</option>
                            @for($i = 00; $i <= 99; $i++)
                            <option value="{{ $i }}" @if(old('bottom_of_time') == $i) selected @endif>{{ Str::substr($i+100,-2) }}</option>
                            {{-- <option value="{{$i}}">{{$i}}</option> --}}
                            @endfor
                        </select>
                        <span>　秒　</span>
                    </div>
                    <div class="flex ml-00 ml-4">
                        <div>
                        <x-label for="laps" value="Lap数" class="mt-0"/>
                        {{-- <x-input id="number_of_sub" class="bg-gray-100 block mt-1 w-full" id="number_of_sub" type="text" name="number_of_sub" :value="old('number_of_sub')" required  /> --}}
                        <select name="laps" class="w-20 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="" @if(\Request::get('laps') == '0') selected @endif >Lap</option>
                            @for($i = 01; $i <= 100; $i++)
                            <option value="{{ $i }}" @if(old('laps') == $i) selected @endif>{{ $i }}</option>
                            {{-- <option value="{{$i}}">{{$i}}</option> --}}
                            @endfor
                        </select>
                        </div>

                    </div>
                </div>

                <div class="ml-2 mr-2 flex">
                    <div>
                        <x-label for="max_rev" value="最高回転数" class="mt-0"/>
                        <select name="max_rev" class="w-24 mr-2 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="{{ $latest->max_rev }}" @if(\Request::get('max_rev') == '0') selected @endif >{{ $latest->max_rev }}</option>
                            @for($i = 13500; $i <= 15000; $i=$i+100)
                            <option value="{{ $i }}" @if(old('max_rev') == $i) selected @endif>{{ $i }}</option>
                            {{-- <option value="{{$i}}">{{$i}}</option> --}}
                            @endfor
                        </select>
                    </div>
                    <div class="mr-4">
                        <x-label for="min_rev" value="最低回転数" class="mt-0"/>
                        <select name="min_rev" class="w-20 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="{{ $latest->min_rev }}" @if(\Request::get('min_rev') == '0') selected @endif >{{ $latest->min_rev }}</option>
                            @for($i = 6500; $i <= 9000; $i=$i+100)
                            <option value="{{ $i }}" @if(old('min_rev') == $i) selected @endif>{{ $i }}</option>
                            {{-- <option value="{{$i}}">{{$i}}</option> --}}
                            @endfor
                        </select>
                    </div>
                    <div class="mr-2">
                        <x-label for="cab_hi" value="キャブHigh" class="mt-0"/>
                        <select name="cab_hi" class="w-20 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="{{ $latest->cab_hi }}" @if(\Request::get('cab_hi') == '0') selected @endif >{{ $latest->cab_hi }}</option>
                            @for($i = 10; $i <= 30; $i++)
                            <option value="{{ $i }}" @if(old('cab_hi') == $i) selected @endif>{{ $i }}</option>
                            {{-- <option value="{{$i}}">{{$i}}</option> --}}
                            @endfor
                        </select>
                    </div>
                    <div>
                        <x-label for="cab_lo" value="キャブLow" class="mt-0"/>
                        <select name="cab_lo" class="w-20 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="{{ $latest->cab_lo }}" @if(\Request::get('cab_lo') == '0') selected @endif >{{ $latest->cab_lo }}</option>
                            @for($i = 80; $i <= 150; $i=$i+5)
                            <option value="{{ $i }}" @if(old('cab_lo') == $i) selected @endif>{{ $i }}</option>
                            {{-- <option value="{{$i}}">{{$i}}</option> --}}
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="ml-2 mr-2 flex">
                    <div class="mr-2">
                        <x-label for="fr_sprocket" value="フロントスプロケ" class="mt-00"/>
                        <select name="fr_sprocket" class="w-24 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="{{ $latest->fr_sprocket }}" @if(\Request::get('fr_sprocket') == '0') selected @endif >{{ $latest->fr_sprocket }}</option>
                            @for($i = 8; $i <= 12; $i++)
                            <option value="{{ $i }}" @if(old('fr_sprocket') == $i) selected @endif>{{ $i }}</option>
                            {{-- <option value="{{$i}}">{{$i}}</option> --}}
                            @endfor
                        </select>
                    </div>
                    <div class="mr-2">
                        <x-label for="re_sprocket" value="リアスプロケ" class="mt-00"/>
                        <select name="re_sprocket" class="w-24 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="{{ $latest->re_sprocket }}" @if(\Request::get('re_sprocket') == '0') selected @endif >{{ $latest->re_sprocket }}</option>
                            @for($i = 75; $i <= 90; $i++)
                            <option value="{{ $i }}" @if(old('re_sprocket') == $i) selected @endif>{{ $i }}</option>
                            {{-- <option value="{{$i}}">{{$i}}</option> --}}
                            @endfor
                        </select>
                    </div>
                    <div>
                        <x-label for="stabilizer" value="スタビ" class="mt-00"/>
                        <select name="stabilizer" class="w-24 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text">
                            <option value="{{ $latest->stabilizer }}">{{ $latest->stabilizer }}</option>
                            <option value="--" @if(old('stabilizer') == "--") selected @endif>なし</option>
                            {{-- <option value="なし">なし</option> --}}
                            {{-- <option value="0度">0度</option> --}}
                            <option value="0°" @if(old('stabilizer') == "0°") selected @endif>0°</option>
                            {{-- <option value="30度">30度</option> --}}
                            <option value="30°" @if(old('stabilizer') == "30°") selected @endif>30°</option>
                            <option value="45°" @if(old('stabilizer') == "45°") selected @endif>45°</option>
                            {{-- <option value="60度">60度</option> --}}
                            <option value="60°" @if(old('stabilizer') == "60°") selected @endif>60°</option>
                            <option value="75°" @if(old('stabilizer') == "75°") selected @endif>75°</option>
                            {{-- <option value="90度">90度</option> --}}
                            <option value="90°" @if(old('stabilizer') == "90°") selected @endif>90°</option>
                        </select>
                    </div>
                </div>


            <div class="ml-2">
                <x-label for="stint_info" value="コメント" class="mt-1"/>
                <x-textarea row="5" id="stint_info" class="bg-gray-100 block mt-1 w-full" type="text" name="stint_info" >{{ old('stint_info')  }}</x-textarea>
            </div>

            <div class="p-0 md:flex">
                <div class="relative">
                    <x-label for="image1" value="サーキット画像" class="mt-00"/>
                    <input type="file" id="image1" name="image1" accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
                <div class="relative">
                    <x-label for="image2" value="フロントタイヤ画像" class="mt-00"/>
                    <input type="file" id="image2" name="image2" accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
                <div class="relative">
                    <x-label for="image3" value="リアタイヤ画像" class="mt-00"/>
                    <input type="file" id="image3" name="image3" accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
            </div>
            </div>
            <div class="flex justify-between">

            <div class="p-2 w-1/2 mx-auto flex">
                <div class="p-2 w-full mt-2 flex justify-around">
                    <button type="submit" class="text-white bg-green-500 border-0 py-2 px-8 focus:outline-none hover:bg-green-600 rounded text-lg">登録</button>
                </div>
            </div>
            </div>
        </form>
            </div>

            </div>
        </div>


    <script>

        // const sub = document.getElementById('number_of_sub')
        // sub.addEventListener('change', function(){
        // this.form.submit()
        // })




    </script>
</x-app-layout>

