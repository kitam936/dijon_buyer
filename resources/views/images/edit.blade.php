<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        画像削除<br>
        　　　　　<span class="text-red-500"> この画像を削除します。</span>
        </h2>

        <div class="m-">
            <input type="hidden" class="pl-0  ml-0 md:ml-2 w-32 h-6 items-center bg-gray-100 border rounded" name="album_id2"  value="{{ $album->album_id }}"/>
            <div class="p-2 w-full flex justify-around mt-2">
                <button type="button" onclick="location.href='{{ route('album_index',['event'=>$album->event_id])}}'" class="w-32 h-8 text-white text-sm bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded ">戻る</button>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 bg-white border-b border-gray-200">
                    {{-- <x-auth-validation-errors class="mb-4" :errors="$errors" /> --}}
                        <img class="w-1/2 mx-auto" src="{{ asset('storage/album/'.$album->filename) }}">

                    <form id="delete_{{$album->album_id}}" method="POST" action="{{ route('album.destroy',['album' => $album->album_id]) }}">
                        @csrf
                        @method('delete')
                            <div class="p-2 w-full flex justify-around mt-2">
                            <a href="#" data-id="{{ $album->album_id }}" onclick="deletePost(this)" class="w-32 h-8 text-center text-white bg-red-400 border-0 py-1 px-4 focus:outline-none hover:bg-red-500 rounded ">削除</a>
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

