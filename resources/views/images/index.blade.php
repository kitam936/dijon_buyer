<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2">
            画像リスト 　<br>
        </h2>
        <div class="md:flex  md:ml-24 mb-2">

            <div class="ml-4 flex mt-2 md:mt-0">
                <div class="ml-0 md:ml-4">
                    <button type="button" class="w-32 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('product_index') }}'" >商品リスト</button>
                </div>

            </div>
        </div>

    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-flash-message status="session('status')"/>

                    <div class="flex flex-wrap">
                    @foreach ($images as $image )
                    <div class="w-full md:w-1/4 p-2 md:p-4">
                    {{-- <a href="{{ route('image.edit',['image'=>$image->id]) }}"> --}}
                    <div class="border rounded-md p-0 md:p-0">
                        <div class="text-gray-700"> {{ $image->hinban_id  }}</div>
                        <div class="text-gray-700"> {{ Str::limit($image->hinban_name,20)  }}</div>
                        <a href="{{ route('image_show',['hinban'=>$image->hinban_id]) }}">
                        <x-image-thumbnail :filename="$image->filename"  />
                        </a>
                        <div class="flex">
                        {{-- <div class="text-gray-700"> {{ Str::limit($image->hinban_name,12)  }}</div> --}}
                        <div class="text-gray-700 ml-4 mr-4"> 売価　{{ $image->m_price  }}円</div>
                        @if ($login_user->role_id <= 2)
                        <a href="{{ route('admin.image_edit',['hinban'=>$image->hinban_id]) }}" ><span class="rounded text-red-500 ml-4 bg-gray-300 border-gray-800">削除</span></a>
                        @endif
                        </div>

                    </div>
                    </a>
                    </div>
                    @endforeach
                    </div>
                    {{ $images->links() }}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
