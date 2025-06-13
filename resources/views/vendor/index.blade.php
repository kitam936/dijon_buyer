
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold mb-2 text-xl text-gray-800 dark:text-gray-200 leading-tight">
            仕入先一覧
        </h2>
        <div class="flex">
            <div class="pl-2 mt-2 ml-4 ">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('menu') }}'" >Menu</button>
             </div>




             <div class="ml-2 mt-2 ml-4 md:mt-2">
                <button type="button" class="w-40 h-8 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('vendor_create') }}'" >新規登録</button>
            </div>
        </div>

        <x-flash-message status="session('status')"/>




    </x-slot>

    <div class="py-0 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-full bg-white table-auto w-full text-center whitespace-no-wrap">
               <thead>
                    <tr>
                        <th class="w-2/12 md:1/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">code</th>
                        <th class="w-3/12 md:2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">社名</th>
                        <th class="w-2/12 md:5/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">info</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($vendors as $vendor)
                    <tr>
                        <td class="w-2/12 md:1/12 text-sm md:px-4 py-1 text-left"> {{ $vendor->id }} </td>
                        <td class="w-3/12 md:2/12 text-sm md:px-4 py-1 text-left"><a href="{{ route('vendor_show',['id'=>$vendor->id]) }}" class="w-20 h-8 text-indigo-500 ml-2 "  >{{ Str::limit($vendor->vendor_name,20) }}</a></td>
                        <td class="w-2/12 md:5/12 text-xs md:px-4 py-1 text-left">{{ Str::limit($vendor->vendor_info,28) }}</td>

                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{  $vendors->links()}}
        </div>
    </div>







</x-app-layout>
