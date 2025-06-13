<x-app-layout>
    <x-slot name="header">

        <h2 class="font-semibold text-xl mb-4 text-gray-800 dark:text-gray-200 leading-tight">
        <div>
            コストレート変更
        </div>
        </h2>
        <div class="ml-8 ">
        <div class="ml-2 mb-2 md:mb-0">
            <button type="button" onclick="location.href='{{ route('data.rate_menu') }}'" class="w-40 h-8 text-center text-sm text-white bg-indigo-400 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-600 rounded ">RateMenu</button>
        </div>
        </div>



            <div class="ml-8 px-2 py-2 text-gray-900 ">

                <form method="POST" action="{{ route('data.cost_rate_update',['id'=>1]) }}">
                    @csrf
                    <div class="">


                    <div class="mt-1 md:mt-8">
                        <label for="cost_rate" class=" text-sm  text-gray-800  ">コストレート</label>
                        <select name="cost_rate" class="rounded h-10">
                            <option value="{{ $cost_rate->cost_rate }}">{{ $cost_rate->cost_rate ?? 0}}%</option>
                            @for ($i = 80; $i <= 200; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                         　※現地コストに対しての倍率（％）

                    </div>
                    <div class="flex pl-0 mt-2 md:mt-2">
                        <label for="cost_memo" class=" text-sm  text-gray-800  mr-2">memo</label>
                        <textarea class="pl-0 ml-4 text-sm md:ml-4 w-full md:w-1/2  border rounded" name="cost_memo" rows="5">{{ $cost_rate->cost_memo }}</textarea>
                    </div>
                    <div class="p-2 md:w-1/2 mx-auto flex">
                        <div class="p-2 w-full md:w-1/2 mt-2 flex justify-around">
                            <button type="submit" class="text-white bg-green-500 border-0 py-2 px-8 focus:outline-none hover:bg-green-600 rounded text-lg">更新</button>
                        </div>
                    </div>

                </form>
            </div>



    </x-slot>


</x-app-layout>
