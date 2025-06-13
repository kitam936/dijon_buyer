<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <div class="flex">
            <div>
            Faceデータ
            </div>
            <div class="w-40 ml-60 text-sm items-right mb-0">
                <button onclick="location.href='{{ route('data.data_index') }}'" class="w-32 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded ">戻る</button>
            </div>
            </div>
        </h2>
        <x-flash-message status="session('status')"/>

    </x-slot>

    <div class="py-6 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-2/3 bg-white table-auto text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Face</th>
                        <th class="w-4/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Item</th>
                        {{-- <th class="w-2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">季節</th> --}}

                    </tr>
                </thead>

                <tbody>
                    @foreach ($faces as $face)
                    <tr>
                        <td class="w-2/12 md:px-4 py-1">{{ $face->face_code }}</td>
                        <td class="w-4/12 md:px-4 py-1"> {{ $face->face_item }}</td>
                        {{-- <td class="w-2/12 md:px-4 py-1"> {{ $face->season_name }}</td> --}}


                    </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
    </div>

</x-app-layout>
