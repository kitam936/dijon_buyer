<x-app-layout>
    <x-slot name="header">
        <div >
            <h2 class="mb-4 font-semibold text-xl  text-gray-800 dark:text-gray-200 leading-tight">
            <div>
                コメント編集
            </div>
            </h2>
            <div class="flex">
            <div class="ml-10 mb-2">
                <button type="button" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('report_list') }}'" >Reportリスト</button>
            </div>
            <div class="ml-4 mb-2">
                <button type="button" onclick="location.href='{{ route('comment_detail',['comment'=>$comment->id]) }}'" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">コメント詳細</button>
            </div>
            <div>
            {{-- <form id="delete_{{$comment->id}}" method="POST" action="{{ route('comment_destroy',['comment'=>$comment->id]) }}">
                @csrf
                @method('delete')
                <div class="ml-0 mt-2 md:ml-4 md:mt-0">
                    <div class="w-32 text-center text-sm text-white bg-red-500 border-0 py-1 px-2 focus:outline-none hover:bg-red-700 rounded ">
                    <a href="#" data-id="{{ $comment->id }}" onclick="deletePost(this)" >削除</a>
                    </div>
                </div>
            </form> --}}

            </div>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- <x-input-error :messages="$errors->get('image')" class="mt-2" /> --}}
                    <form method="post" action="{{ route('comment_update',['comment'=>$comment->id]) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="-m-2">
                        <div class="flex mb-0">
                            <div class="mb-2 md:ml-2 ">
                                <input type="hidden" class="pl-0  ml-0 md:ml-2 w-32 h-6 items-center bg-gray-100 border rounded" name="comment_id2"  value="{{ $comment->id }}"/>
                            </div>
                            <div class="flex pl-0 mt-0">
                                <input type="hidden" class="pl-0  ml-0 md:ml-2 w-32 h-6 items-center bg-gray-100 border rounded" name="user_id2"  value="{{ $login_user->id }}"/>
                            </div>

                            <div class="mb-2 md:ml-2 ">
                                <x-label for="name" value="投稿者" />
                                <div class="pl-2 w-72 h-6 text-sm items-center bg-gray-100 border rounded" name="name" value="{{ $comment->name }}">{{ $comment->name }}</div>
                                <input type="hidden" class="pl-2 w-full h-6 text-sm items-center bg-gray-100 border rounded" name="report_id2" value="{{ $comment->report_id }}"/>
                                <input type="hidden" class="pl-0  ml-0 md:ml-2 w-32 h-6 items-center bg-gray-100 border rounded" name="user_id2"  value="{{ $comment->user_id }}"/>
                            </div>
                        </div>
                        <div class="p-2 mx-auto">
                            <div>
                                <x-label for="comment" value="コメント　※必須" />
                                <x-textarea rows="5" id="comment" class="block mt-1 w-full" type="text" name="comment" required>{{ $comment->comment}}</x-textarea>
                            </div>
                        </div>

                        <div class="p-2 w-1/2 mx-auto">
                        <div class="p-2 w-full mt-4 flex justify-around">
                            <button type="submit" class="w-32 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded ">更新</button>
                        </div>
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

