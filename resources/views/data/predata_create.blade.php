<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            事前品番登録
        </h2>

        <x-flash-message status="session('status')"/>

        <div class="flex mt-4">
        <div class="ml-2 md:ml-4">
            <button type="button" class="ml-2 mb-4 h-8 w-40 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('data.predata_index') }}'" >事前品番リスト</button>
        </div>
        {{-- <div class="ml-2 md:ml-4">
            <button type="button" class="ml-2 mb-4 h-8 w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-600 rounded " onclick="location.href='{{ route('hinban_create2') }}'" >前回コピー</button>
        </div> --}}

        </div>

        <form method="POST" action="{{ route('data.predata_store') }}" >
            @csrf

            <div class="md:flex">

                <div class="md:flex">
                    <div class="flex">
                    <div class="relative ml-2 mr-0">
                        <x-label for="year_code" value="年CD" />
                        <select  id="year_code" name="year_code"  class="w-28 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="" @if(\Request::get('year_code') == '0') selected  @endif >年CD</option>

                            @foreach ($years as $year)
                                <option value="{{ $year->year_code }}" @if(\Request::get('year_code') == $year->year_code) selected @endif>{{ $year->year_code }}</option>

                            @endforeach
                        </select>
                    </div>

                    <div class="relative ml-2 mr-0">
                        <x-label for="shohin_gun" value="商品群" />
                        <select  id="shohin_gun" name="shohin_gun"  class="w-28 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="" @if(\Request::get('shohin_gun') == '0') selected  @endif >商品群</option>

                            @foreach ($guns as $year)
                                <option value="{{ $year->shohin_gun }}" @if(\Request::get('shohin_gun') == $year->shohin_gun) selected @endif>{{ $year->shohin_gun }}</option>

                            @endforeach
                        </select>
                    </div>
                    </div>

                    <div class="flex mt-0">
                    <div class="relative ml-2 mr-0">
                    <x-label for="brand_id" value="Brand" />
                    <select  id="brand_id" name="brand_id"  class="w-28 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                        <option value="" @if(\Request::get('brand_id') == '0') selected  @endif >Brand</option>

                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @if(\Request::get('brand_id') == $brand->id) selected @endif>{{ $brand->id }}={{ $brand->brand_name }}</option>

                        @endforeach
                    </select>
                    </div>

                    <div class="flex relative ">

                        <div class="relative ml-2">
                            <x-label for="unit_id" value="Unit" />
                            <select  id="unit_id" name="unit_id"  class="w-28 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="" @if(\Request::get('unit_id') == '0') selected @endif >Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" @if(\Request::get('unit_id') == $unit->id) selected @endif>{{ $unit->id }}</option>

                            @endforeach
                            </select>
                        </div>
                    </div>
                    </div>

                    <div class="flex mt-0">
                    <div class="relative ml-2 mr-0">
                        <x-label for="face_code" value="Face" />
                        <select  id="face_code" name="face_code"  class="w-28 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                        <option value="" @if(\Request::get('face_code') == '0') selected @endif >Face</option>

                        @foreach ($faces as $face)
                            <option value="{{ $face->face_code }}" @if(\Request::get('face_code') == $face->face_code) selected @endif>{{ $face->face_code }}_{{ $face->face_item }}</option>

                        @endforeach
                        </select>
                    </div>
                    <div class="pl-0 mt-0 ml-2">
                        <x-label for="hinban_id" value="品番" />
                        <input class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="hinban_id" value="{{ old('hinban_id') }}"></input>
                    </div>
                </div>
                </div>
                </div>
                <div class="p-2 w-1/2 ml-12 md:ml-60 flex">
                    <div class="p-2 w-full md:w-1/2 mt-2 flex justify-around">
                        <button type="submit" class="text-white bg-green-500 border-0 py-2 px-8 focus:outline-none hover:bg-green-600 rounded text-lg">登録</button>
                    </div>
                </div>
        </form>

    </x-slot>

    <script>



    </script>
</x-app-layout>

