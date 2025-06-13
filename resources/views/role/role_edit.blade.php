<x-app-layout>
    <x-slot name="header">

        <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-200 leading-tight">
        <div>
            権限変更
        </div>
        </h2>
        <div class="md:flex ml-8 ">
        <div class="ml-2 mb-2 md:mb-0">
            <button type="button" onclick="location.href='{{ route('role_list') }}'" class="w-40 h-8 text-center text-sm text-white bg-indigo-400 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded ">権限管理</button>
        </div>
        </div>
        <div class="-m-2">



    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:w-2/3 px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-2 py-2 text-gray-900 dark:text-gray-100">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}

                        <div class="px-2 py-1 ">
                            <div class="flex">
                            <div class="relative ">
                                <label for="user_id" class="ml-2 leading-7 text-sm  text-gray-800 dark:text-gray-200 ">No</label>
                                <div id="user_id" name="user_id" value="{{ $user->id }}" required class="w-20 bg-gray-100 bg-opacity-50 rounded border text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $user->id }}</div>
                            </div>

                            <div class="relative">
                                <label for="name" class="ml-2 leading-7 text-sm  md:ml-4 text-gray-800 dark:text-gray-200 ">Name</label>
                                <div id="name" name="name" value=" {{ $user->name }}" class="w-60 ml-4 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $user->name }}</div>
                            </div>
                            </div>
                        </div>

                    <form method="post" action="{{ route('role_update',['user'=>$user->id])}}" enctype="multipart/form-data">
                    @csrf


                    <div class="relative ml-4 mt-2 px-2 py-1">
                        <x-label for="role_id" value="権限" />
                            <select class="w-80 h-8 rounded text-sm pt-1" id="role_id" name="role_id"  class="border">
                                <option value="" @if(\Request::get('role_id') == '0') selected @endif >{{ $user->role_name }}</option>
                                <option value="{{ $user->role_id }}" @if(\Request::get('role_id') == '0') selected @endif ></option>
                                @foreach ($changeable_roles as $role)
                                    <option value="{{ $role->id }}" @if(\Request::get('role_id') == $role->id) selected @endif >{{ $role->role_name }}</option>
                                @endforeach
                                </select>
                    </div>
                </div>


                        <div class="p-2 w-1/2 mx-auto flex">
                        <div class="p-2 w-full mt-2 flex justify-around">
                            <button type="submit" class="text-white h-8 w-32 bg-green-500 border-0 py-0 px-8 focus:outline-none hover:bg-green-600 rounded text-lg">更新</button>
                        </div>

                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
