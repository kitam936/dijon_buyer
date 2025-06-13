<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        　パスワード変更_Admin
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100">
                    <section class="text-gray-600 body-font relative">
                        <div class="container px-5 py-4 mx-auto">
                        <div class="flex flex-col text-center w-full mb-2">
                            <h1 class="sm:text-3xl text-2xl font-medium title-font mb-2 text-gray-900">パスワード変更_Admin</h1>
                        </div>
                        <div class="lg:w-1/2 md:w-2/3 mx-auto">
                            {{-- <x-input-error2 :messages="$errors->get('email')" class="mt-2" /> --}}
                            {{-- <x-input-error2 :messages="$errors->get('current-password')" class="mt-2" /> --}}
                            <x-input-error2 :messages="$errors->get('new-password')" class="mt-2" />
                            <x-input-error2 :messages="$errors->get('password-confirmation')" class="mt-2" />
                            <form method="post" action="{{ route('pw_update_admin',['user'=>$target_user->id]) }}">
                            {{-- @method('put') --}}
                            @csrf
                            <x-flash-message status="session('status')"/>

                            <div class="-m-2">
                            <div class="p-2 w-full mx-auto">
                                <div class="relative">
                                <label for="name" class="leading-7 text-sm text-gray-600">ネーム</label>
                                <div type="text" id="name" name="name" value="{{ $user->name }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $target_user->name }}
                                </div>
                                </div>
                                {{-- <div class="p-2 w-full mx-auto"> --}}
                                {{-- <div class="relative">
                                    <label for="current-password" class="leading-7 text-sm text-gray-600">現パスワード</label>
                                    <input type="password"  id="current-password" name="current-password" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div> --}}
                                <div class="relative">
                                    <label for="new-password" class="leading-7 text-sm text-gray-600">新パスワード</label>
                                    <input type="password" id="new-password" name="new-password" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                <div class="relative">
                                    <label for="password-confirmation" class="leading-7 text-sm text-gray-600">新パスワード確認</label>
                                    <input type="password" id="password-confirmation" name="password-confirmation" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                {{-- </div> --}}

                                <div class="p-2 w-full mt-4 flex justify-around">
                                <button type="button" onclick="location.href='{{ route('memberlist') }}'" class="text-black bg-gray-300 border-0 py-2 px-8 focus:outline-none hover:bg-gray-300 rounded text-lg">戻る</button>
                                <button type="submit" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新</button>
                                </div>
                            </div>
                            </div>
                            </form>
                        </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
