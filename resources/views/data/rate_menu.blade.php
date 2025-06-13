<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Rate管理Menu
        </h2>

        <x-flash-message status="session('status')" />

        <div class="md:flex-auto p-1 text-gray-900 dark:text-gray-100  ">
            <div class="md:flex py-4 ml-4 md:w-2/3">
            <div class="flex">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('menu') }}'" >Menu</button>
            {{-- <button type="button" class="w-32 ml-2 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('data.data_index') }}'" >DataMenu</button> --}}
            </div>
            <div class="flex md:mt-0 mt-2">
            <button type="button" class="w-40 h-8 md:ml-2 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('data.ex_rate_edit',['id'=>1]) }}'" >為替レート変更</button>
            <button type="button" class="w-40 h-8 ml-2 text-center text-sm text-white bg-green-500 border-0 py-1 px-2 focus:outline-none hover:bg-green-700 rounded " onclick="location.href='{{ route('data.cost_rate_edit',['id'=>1]) }}'" >コストレート変更</button>
            </div>
            </div>


        </div>

        <div class="">
            <div class="ml-4 flex pl-0 mt-0">
                <label for="vendor_id" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">為替レート</label>
                <div class="pl-2 ml-0 md:ml-2 w-60 h-6 text-sm items-center bg-gray-100 border rounded" name="ex_rate"  value="">1現地通貨　=　{{ $ex_rate->ex_rate /100}}円</div>

            </div>

            <div class="ml-4 flex pl-0 mt-2 md:mt-2 ">
                <label for="vendor_name" class="leading-7 text-sm  text-gray-800 dark:text-gray-200 ">コストレート</label>
                <div class="ml-1 pl-2 w-60 h-6 text-sm items-center bg-gray-100 border rounded" name="cost_rate" value="">現地コスト　×　{{ $cost_rate->cost_rate }}％</div>
            </div>
            </div>

    </x-slot>

</x-app-layout>
