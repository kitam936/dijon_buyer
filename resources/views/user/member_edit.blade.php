<x-app-layout>
    <x-slot name="header">

        <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-200 leading-tight">
        <div>
            Member情報編集
        </div>
        </h2>
        <div class="md:flex ml-8 ">
        <div class="ml-2 mb-2 md:mb-0">
            <button type="button" onclick="location.href='{{ route('memberlist') }}'" class="w-40 h-8 text-center text-sm text-white bg-indigo-400 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded ">Membert一覧</button>
        </div>
        </div>

    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:w-2/3 px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-2 py-2 text-gray-900 dark:text-gray-100">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}
                    <form method="post" action="{{ route('member_update1',['user'=>$user->id])}}" >
                    @csrf

                    <div class="-m-2">
                        <div class="px-2 mx-auto">
                            <div class="flex ml-0 mb-2  md:mb-1">
                                <div class="relative ml-0 mt-2 px-0 py-1">
                                    <div>
                                    <label for="sh_id" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">所属</label>
                                    </div>
                                    {{-- <div>
                                        <select class="w-80 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" id="sh_id" name="sh_id"  class="border">
                                            <option value="" @if(\Request::get('sh_id') == '0') selected @endif >{{ $user->shop_name }}</option>
                                            <option value="{{ $user->shop_id }}" @if(\Request::get('sh_id') == '0') selected @endif ></option>
                                            @foreach ($shops as $shop)
                                                <option value="{{ $shop->id }}" @if(\Request::get('sh_id') == $shop->id) selected @endif >{{ $shop->shop_name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="relative">
                                <label for="name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">名前</label>
                                <input id="name" name="name" value=" {{ $user->name }}" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"></input>
                            </div>

                            <div class="relative">
                                <label for="email" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Mail</label>
                                <input id="email" name="email" value=" {{ $user->email }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"></input>
                            </div>
                            <div class="p-2 w-1/2 ">
                                <label for="mailService" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">メール配信</label>
                                <div class="relative flex justify-around">
                                    <div><input type="radio" name="mailService" value="1" class="mr-2" @if($user->mailService == 1){ checked }@endif>配信可</div>
                                    <div><input type="radio" name="mailService" value="0" class="mr-2" @if($user->mailService == 0){ checked }@endif>配信不可</div>
                                </div>
                            </div>
                            <div class="relative">
                                <label for="user_info" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">info</label>
                                <textarea id="user_info" name="user_info" rows="8" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $user->user_info }}</textarea>
                            </div>
                        </div>



                        <div class="p-2 w-1/2 mx-auto flex">
                        <div class="p-2 w-full mt-2 flex justify-around">
                            <button type="submit" class="text-white bg-green-500 border-0 py-2 px-8 focus:outline-none hover:bg-green-600 rounded text-lg">更新</button>
                        </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
