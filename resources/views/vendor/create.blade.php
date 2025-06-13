
<x-app-layout>
    <x-slot name="header">

        <h2 class="mb-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            仕入先登録
            {{-- <button type="button" onclick="location.href='{{ route('user.vendormpany.index') }}'" class="mb-2 ml-2 text-right text-black bg-indigo-300 border-0 py-0 px-2 focus:outline-none hover:bg-indigo-300 rounded ">戻る</button> --}}
        </h2>
        <div class="ml-2 flex mb-4 md:ml-4">
        <div class="ml-2 mt-2 md:mt-0 md:ml-8">
            <button type="button" class="w-40 h-8 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('vendor_index') }}'" >仕入先一覧</button>
        </div>
        </div>

        <x-input-error :messages="$errors->get('vendor_id')" class="mt-2" />
        <x-input-error :messages="$errors->get('vendor_name')" class="mt-2" />
        <x-input-error :messages="$errors->get('vendor_info')" class="mt-2" />



        <form method="post" action="{{ route('vendor_store') }}">
            @csrf
            <div class="">

                <div class="">
                <div class="flex pl-0 mt-0">
                    <label for="vendor_id" class="leading-7 text-sm  text-gray-800  ">code</label>
                    <input class="pl-2 ml-0 md:ml-2 w-24 h-6 text-sm items-center bg-gray-100 border rounded" name="vendor_id" value="{{ old('vendor_id') }}"></input>
                </div>

                <div class="flex pl-0 mt-2 md:mt-2 ">
                    <label for="vendor_name" class="leading-7 text-sm  text-gray-800  ">社名</label>
                    <input class="ml-3 pl-2 w-32 h-6 text-sm items-center bg-gray-100 border rounded" name="vendor_name" value="{{ old('vendor_name') }}"></input>
                </div>
                </div>


                <div class="flex pl-0 mt-2 md:mt-2">
                    <label for="vendor_info" class=" text-sm  text-gray-800  ">info</label>
                    <textarea class="pl-0 ml-0 text-sm md:ml-4 w-full md:w-1/2  items-center bg-gray-100 border rounded" name="vendor_info" rows="10">{{ old('vendor_info') }}</textarea>
                </div>


                <div class="p-2 md:w-1/2 mx-auto flex">
                    <div class="p-2 w-full md:w-1/2 mt-2 flex justify-around">
                        <button type="submit" class="text-white bg-green-500 border-0 py-2 px-8 focus:outline-none hover:bg-green-600 rounded text-lg">登録</button>
                    </div>
                </div>
            </div>
     </form>


    </x-slot>


</x-app-layout>
