
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold mb-2 text-xl text-gray-800 leading-tight">
            Member権限リスト
        </h2>

         <div class="ml-2 md:ml-4">
                    <button type="button" class="w-40 h-8 text-sm bg-indigo-500 text-white ml-2 hover:bg-indigo-600 rounded"  onclick="location.href='{{ route('memberlist') }}'" >Memberリスト</button>
        </div>


        <form method="get" action="{{ route('role_list')}}" class="mt-4">
            <x-flash-message status="session('status')"/>
            <span class="items-center text-sm mt-2 text-gray-800 leading-tight" >※権限を選択してください　　　</span>
            <div class="md:flex">
            <div class="flex">

                <div class="flex ml-0 mb-2 md:flex md:mb-4">
                        <select class="w-32 h-8 ml-0 rounded text-sm pt-1 " id="role_id" name="role_id"  class="border">
                        <option value="" @if(\Request::get('role_id') == '0') selected @endif >全て</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @if(\Request::get('role_id') == $role->id) selected @endif >{{ $role->role_name }}</option>
                        @endforeach
                        </select><br>
                </div>
                </div>
                <div class="flex mb-2 md:flex md:mb-4">
                        {{--  <label class="items-center ml-2 mr-1 text-sm mt-2 text-gray-800 leading-tight" >検索</label>  --}}
                        <input class="w-44 h-8 ml-0 md:ml-4 rounded text-sm pt-1" id="user_name" placeholder="Name入力検索" name="user_name"  class="border">

                <div class="ml-2 md:ml-4">
                    <button type="button" class="w-20 h-8 text-sm bg-blue-500 text-white ml-2 hover:bg-blue-600 rounded" onclick="location.href='{{ route('role_list') }}'" >全表示</button>
                </div>


            </div>

            </div>
        </form>

    </x-slot>

    <div class="py-0 border">
        <div class=" mx-auto sm:px-4 lg:px-4 border ">
            <table class="md:w-full bg-white table-auto w-full text-center whitespace-no-wrap">
                <thead>
                    <tr>
                        <th class="w-1/12 md:1/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ID</th>
                        <th class="w-2/12 md:2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">権限</th>
                        <th class="w-2/12 md:2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Name</th>
                        <th class="w-3/12 md:5/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">info</th>
                        <th class="w-2/12 md:2/12 md:px-4 py-1 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">変更</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($changeable_users as $user)
                    <tr>
                        <td class="w-1/12 md:1/12 text-sm md:px-4 py-1 text-center"> {{ $user->id }} </td>
                        <td class="w-2/12 md:2/12 text-sm md:px-4 py-1 text-center">{{ ($user->role_name) }}</td>
                        <td class="w-2/12 md:2/12 text-sm md:px-4 py-1 text-center">{{ Str::limit($user->name,20) }}</td>
                        <td class="w-3/12 md:5/12 text-xs md:px-4 py-1 text-center">{{ Str::limit($user->user_info,28) }}</td>
                        <td class="w-2/12 md:2/12 text-sm md:px-4 py-1 text-indigo-500 text-center"><a href="{{ route('role_edit',['user'=>$user->id]) }}" >変更</a></td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
            {{  $users->appends([
                'ar_id'=>\Request::get('ar_id'),
                'role_id'=>\Request::get('role_id'),
                'user_name'=>\Request::get('user_name'),
            ])->links()}}
        </div>
    </div>





        <script>
            const role = document.getElementById('role_id')
            role.addEventListener('change', function(){
            this.form.submit()
            })



            const user_name = document.getElementById('user_name')
            user_name.addEventListener('change', function(){
            this.form.submit()
            })


        </script>

</x-app-layout>
