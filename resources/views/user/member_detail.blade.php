<x-app-layout>
    <x-slot name="header">

        <h2 class="font-semibold text-xl mb-4 text-gray-800 leading-tight">
            <div>
                Member詳細
            </div>
            </h2>
            <div class="md:flex ml-2 ">
            <div class="ml-2 mb-2 md:mb-0">
                <button type="button" onclick="location.href='{{ route('memberlist') }}'" class="w-40 h-8 text-center text-sm text-white bg-indigo-400 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded ">Memberリスト</button>
            </div>


            {{-- @foreach ($users as $user) --}}
            @if(Auth::User()->role_id <=4)

            <div class="flex mb-2 md:ml-32">
                <div class="ml-2 mb-0 md:mb-0">
                    <button type="button" onclick="location.href='{{ route('user_edit',['user'=>$user->id])}}'" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-600 rounded ">編集</button>
                </div>
                <div class="ml-2 mb-0 md:mb-0">
                    <button type="button" onclick="location.href='{{ route('pw_change_admin',['user'=>$user->id])}}'" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-600 rounded ">パスワード変更</button>
                </div>
            </div>

            <form id="delete_{{$user->id}}" method="POST" action="{{ route('user_destroy',['user'=>$user->id]) }}">
                @csrf
                @method('delete')
                <div class="ml-2 mt-0 md:ml-4 md:mt-0">
                    <div  class="w-40 h-8 text-center text-sm text-white bg-red-500 border-0 py-1 px-2 focus:outline-none hover:bg-red-600 rounded ">
                    <a href="#" data-id="{{ $user->id }}" onclick="deletePost(this)" >メンバー削除</a>
                    </div>
                </div>
            </form>

          @endif


            </div>


    </x-slot>


    <div class="py-4">
        <div class="max-w-7xl sm:px-6 lg:w-2/3 px-0">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 bg-white border-b border-gray-200">

                    <form method="get" action=""  enctype="multipart/form-data">
                        <x-flash-message status="session('status')"/>
                        <div class="-m-2">
                            <div class="p-2 mx-auto">

                                <div class="p-2 w-full mx-auto">
                                    <div class="flex mx-auto">
                                        <div class="w-20 relative mr-2">
                                            <label for="id" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">No</label>
                                            <div  id="id" name="id" value="{{$user->id}}" class="pl-2 w-20 h-6 text-sm items-center bg-gray-100 border rounded">{{$user->id}}
                                            </div>
                                        </div>

                                    </div>

                                    {{-- <div class="relative ">
                                        <label for="shop_name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">所属</label>
                                        <div  id="shop_name" name="shop_name" value="{{$user->shop_name}}" class="pl-2 w-60 h-6 text-sm items-center bg-gray-100 border rounded">{{$user->shop_name}}
                                        </div>
                                    </div> --}}

                                    <div class="relative ">
                                    <label for="user_name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">ネーム</label>
                                    <div  id="user_name" name="user_name" value="{{$user->name}}" class="pl-2 w-60 h-6 text-sm items-center bg-gray-100 border rounded">{{$user->name}}
                                    </div>
                                    </div>


                                    @if($login_user->id == $user->id)
                                    <div class="relative">
                                        <label for="email" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Mail　</label>
                                        <div  id="email" name="email" value="{{$user->email}}" class="pl-2 w-60 h-6 text-sm text-indigo-600 items-center bg-gray-100 border rounded"><a href="mailto:{{ $user->email }}">{{$user->email}}</a>
                                        </div>
                                    </div>
                                    @endif
                                    @if(!empty($user->user_info))
                                    <div class="relative">
                                        <label for="user_info" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">Info</label>
                                        <div  id="user_info" name="user_info" value="{!! nl2br(e($user->user_info)) !!}" class="text-sm w-full bg-gray-100 bg-opacity-50 border rounded focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{!! nl2br(e($user->user_info)) !!}
                                        </div>
                                    </div>
                                    @endif
                                    </div>

                            </div>




                                {{-- @endforeach --}}
                            </div>

                        </div>
                    </form>
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
