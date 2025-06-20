<x-app-layout>
    <x-slot name="header">

        <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-200 leading-tight">
        <div>
            コメント詳細
        </div>
        </h2>
        <div class="md:flex md:ml-8 ">

            <input type="hidden" class="pl-0  ml-0 md:ml-2 w-40 h-8 h-6 items-center bg-gray-100 border rounded" name="hinban_id2"  value="{{ $comment->hinban_id }}"/>
            <div class="md:ml-10 mb-2">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('hinban_show',['id'=>$comment->hinban_id]) }}'" >商品詳細</button>
            </div>
            <div class="md:ml-4 mb-2">
                {{-- <button type="button" onclick="location.href='{{ route('event_detail',['event'=>$comment->event_id]) }}'" class="w-40 h-8 bg-indigo-500 text-white ml-2 hover:bg-indigo-600 rounded">イベント詳細</button> --}}
            </div>


            <div class="flex">
            @if($login_user->id == $comment->user_id)
            <div class="md:ml-2 mb-2 md:mb-0">
                <button type="button" onclick="location.href='{{ route('comment_edit',['comment'=>$comment->id])}}'" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded ">編集</button>
            </div>

            <div class="ml-4">
            <form id="delete_{{$comment->id}}" method="POST" action="{{ route('comment_destroy',['comment'=>$comment->id]) }}">
                @csrf
                @method('delete')
                <div class="ml-0 mt-0 md:ml-2 md:mt-0">
                    <div class="w-40 h-8 text-center text-sm text-white bg-red-500 border-0 py-2 px-2 focus:outline-none hover:bg-red-700 rounded ">
                    <a href="#" data-id="{{ $comment->id }}" onclick="deletePost(this)" >削除</a>
                    </div>
                </div>
            </form>
            </div>
            @endif
        </div>


    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 bg-white border-b border-gray-200">

                    <form method="get" action=""  enctype="multipart/form-data">

                        <div class="-m-2">
                            <div class="p-2 mx-auto">

                                <div class="p-2 w-full mx-auto">
                                    <div class="mb-2">
                                        <x-label for="Date" value="Date" />
                                        <div class="pl-2 w-full h-6 text-sm items-center bg-gray-100 border rounded" name="created_at" >{{ $comment->created_at }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <x-label for="user_name" value="Name" />
                                        <div class="pl-2 w-full h-6 text-sm items-center bg-gray-100 border rounded" name="user_name" >{{ $comment->name }}</div>
                                    </div>

                                    <div class="mb-2">
                                        <x-label for="comment" value="コメント" />
                                        <div  rows="5" class="pl-2 w-full text-sm items-center bg-gray-100 border rounded" name="comment" > {!! nl2br(e($comment->comment)) !!}</div>
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
