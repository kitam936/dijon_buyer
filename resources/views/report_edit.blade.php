<x-app-layout>
    <x-slot name="header">
        <div >
            <h2 class="mb-4 font-semibold text-xl  text-gray-800 dark:text-gray-200 leading-tight">
            <div>
                店舗Report編集
            </div>
            </h2>
            <div class="flex">
            <div class="md:ml-20 mb-2 md:mb-0">
                <button type="button" onclick="location.href='{{ route('report_list') }}'" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">店舗Report一覧</button>
            </div>
            <div class="ml-4 mb-2 md:mb-0">
                <button type="button" onclick="location.href='{{ route('shop_index') }}'" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">店舗一覧</button>
            </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:w-2/3 px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-2 py-2 text-gray-900 dark:text-gray-100">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}
                    <form method="post" action="{{ route('report_update',['report'=>$report->id])}}" enctype="multipart/form-data">
                    @csrf
                    <div class="-m-2">
                        <div class="px-2 py-1 mx-auto">
                            <div class="relative flex">
                            <div>
                            <label for="sh_id" class="p-2 w-28 leading-7 text-sm text-gray-600">Shop</label>
                            <div class="flex">
                              <div id="sh_id" name="sh_id" value="{{ $report->shop_id }}" required class="w-20 ml-2 bg-gray-100 bg-opacity-50 rounded border text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $report->shop_id }}</div>
                              {{--  <label for="sh_name" class="p-2 ml-3 w-16 leading-7 text-sm text-gray-600"></label>  --}}
                              <div id="sh_name" name="sh_name" value="{{ $report->shop_name }}" required class="w-80 ml-2 bg-gray-100 bg-opacity-50 rounded border text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $report->shop_name }}</div>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="px-2 mx-auto ml-2">
                            <div class="relative">
                              <label for="report" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Report</label>
                              <textarea id="report" name="report" rows="8" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $report->report }}</textarea>
                            </div>
                        </div>
                        <div class="px-2 md:w-2/1 mx-auto">
                            <div class="relative flex">
                            <div class="w-80 ml-2">
                                <x-report-thumbnail :filename="$report->image1" />
                            </div>
                            <div class="w-80 ml-2">
                                <x-report-thumbnail :filename="$report->image2" />
                            </div>
                            <div class="w-80 ml-2">
                                <x-report-thumbnail :filename="$report->image3" />
                            </div>
                            <div class="w-80 ml-2">
                                <x-report-thumbnail :filename="$report->image4" />
                            </div>
                            </div>
                        </div>
                        <div class="p-0 md:flex">
                        <div class="relative">
                            <label for="image1" class="leading-7 text-sm text-gray-600">画像1</label>
                            <input type="file" id="image1" name="image1" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <div class="relative">
                            <label for="image2" class="leading-7 text-sm text-gray-600">画像2</label>
                            <input type="file" id="image2" name="image2" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <div class="relative">
                            <label for="image3" class="leading-7 text-sm text-gray-600">画像3</label>
                            <input type="file" id="image3" name="image3" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <div class="relative">
                            <label for="image4" class="leading-7 text-sm text-gray-600">画像4</label>
                            <input type="file" id="image4" name="image4" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        </div>


                        <div class="p-2 w-1/2 mx-auto flex">
                        <div class="p-2 w-full mt-2 flex justify-around">
                            <button type="submit" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded ">更新</button>
                        </div>
                        </div>
                    </div>
                    </form>

                    {{-- @if($login_user->id == $report->user_id)
                    <form id="delete_{{$report->id}}" method="POST" action="{{ route('report_destroy',['report' => $report->id]) }}">
                        @csrf
                        @method('delete')
                        <div class="md:px-4 py-3">
                            <div class="p-2 w-full flex justify-around mt-0">
                            <a href="#" data-id="{{ $report->id }}" onclick="deletePost(this)" class="w-32 text-center text-sm text-white bg-red-500 border-0 py-1 px-2 focus:outline-none hover:bg-red-700 rounded  ">削除</a>
                            </div>
                        </div>
                    </form>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        function deletePost(e) {
        'use strict';
        if (confirm('本当に削除してもいいですか?')) {
        document.getElementById('delete_' + e.dataset.id).submit();
        }
        }
    </script>
</x-app-layout>

