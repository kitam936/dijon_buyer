<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            商品編集
        </h2>

        <x-flash-message status="session('status')"/>

        <div class="flex mt-4">
        <div class="ml-2 md:ml-4">
            <button type="button" class="ml-2 mb-4 h-8 w-40 text-center text-sm text-white bg-indigo-500 border-0 py-1 px-2 focus:outline-none hover:bg-indigo-700 rounded " onclick="location.href='{{ route('hinban.hinban_index2') }}'" >商品</button>
        </div>

        </div>

        <div class="flex">
            <div class="pl-0 mt-0 ml-2">
                <x-label for="unit_id" value="Unit" />
                <div class="w-12 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="hinban_id" value="{{ $prod->unit_id }}">{{ $prod->unit_id }}</div>
            </div>
            <div class="pl-0 mt-0 ml-2">
                <x-label for="face_code" value="Face" />
                <div class="w-12 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="hinban_id" value="{{ $prod->face_code }}">{{ $prod->face_code }}</div>
            </div>
            <div class="pl-0 mt-0 ml-2">
                <x-label for="hinban_id" value="品番" />
                <div class="w-32 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="hinban_id" value="{{ $prod->hinban_id }}">{{ $prod->hinban_id }}</div>
            </div>
            </div>


    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto ">
            <div class="bg-white text-indigo-600 overflow-hidden shadow-xl sm:rounded-lg">

            <div CLASS="ml-3 max-w-2xl mx-auto">

                <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('hinban_update',['id' => $prod->hinban_id]) }}" enctype="multipart/form-data">
                @csrf
                <div>
                    <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}" />
                </div>

                <div class="pl-0 mt-0 ml-2">
                    {{-- <x-label for="vendor_id2" value="Vendor" /> --}}
                    <input type="hidden" readonly class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="vendor_id" value="{{ $prod->vendor_id }}"></input>
                </div>
                <div class="md:flex">
                    <div class="ml-0 mb-2 flex md:mb-4">
                        <div>
                            <input type="hidden" id="user_id" name="user_id" value="{{ Auth::User()->id }}" />
                        </div>
                        <div class="relative ml-2 mr-0">
                            <x-label for="vendor_id" value="仕入先" />
                            <select  id="vendor_id" name="vendor_id"  class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                                <option value="{{ $prod->vendor_id }}" @if(\Request::get('vendor_id') == '0') selected  @endif >{{ $prod->vendor_name }}</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" @if(\Request::get('vendor_id') == $vendor->id) selected @endif>{{ $vendor->id }}={{ $vendor->vendor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pl-0 mt-0 ml-2">
                            <x-label for="prod_code" value="先方品番" />
                            <input class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="prod_code" value="{{ $prod->prod_code }}"></input>
                        </div>
                    </div>

                </div>

                <div class="pl-0 mt-2 md:mt-2 ml-2 ">
                    <x-label for="hinban_name" value="品名" />
                    <input class="w-full md:w-1/2 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="hinban_name" value="{{ $prod->hinban_name }}"></input>
                </div>

                <div class="ml-2">
                    <x-label for="hinban_info" value="Info" class="mt-1"/>
                    <x-textarea row="5" id="hinban_info" class="bg-gray-100 block mt-1 w-full" type="text" name="hinban_info" >{{ $prod->hinban_info }}</x-textarea>
                </div>
                <div class="pl-0 mt-0 ml-2">
                    <x-label for="local_cur_price" value="現地価格" />
                    <input type="number" class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="local_cur_price" value="{{ $prod_sku1->local_cur_price }}"></input>
                </div>

                <div class="pl-0 mt-2 md:mt-2 ml-2 ">
                    <x-label for="mix_rate" value="混率" />
                    <input class="w-full md:w-1/2 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="mix_rate" value="{{ $prod->mix_rate }}"></input>
                </div>

                <div class="p-0 ml-2">
                    <div class="relative mt-2">
                        <x-label for="sku1" value="SKU1" class="mt-00"/>
                        <div class="md:flex">
                        <div class="flex">
                        <input type="hidden" class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="sku_id1" value="1"></input>
                        <select  id="col_id1" name="col_id1"  class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku1->col_id }}" @if(\Request::get('col_id1') == '0') selected @endif >{{ $prod_sku1->col_id }}</option>

                            @foreach ($cols as $col)
                                <option value="{{ $col->id }}" @if(old('col_id1') == $col->id) selected @endif>{{ $col->id }}={{ $col->col_name }}</option>

                            @endforeach
                        </select>

                        <select  id="size_id1" name="size_id1"  class="w-20 h-10 ml-2 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku1->size_id }}" @if(\Request::get('size_id1') == '0') selected @endif >{{ $prod_sku1->size_id }}</option>

                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}" @if(old('size_id1') == $size->id) selected @endif>{{ $size->id }}={{ $size->size_name }}</option>

                            @endforeach
                        </select>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="length" value=" 着丈1" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="着丈"  name="length1" value="{{ $prod_sku1->length }}"></input>
                        </div>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="width" value="身幅" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="身幅"  name="width1" value="{{ $prod_sku1->width}}"></input>
                        </div>
                        </div>
                        <input type="file" id="image1" name="image1" accept=“image/png,image/jpeg,image/jpg” class="md:ml-2 w-100 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>

                    <div class="relative">
                        <x-label for="sku2" value="SKU2" class="mt-00"/>
                        <div class="md:flex">
                        <div class="flex">
                        <input type="hidden" class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="sku_id2" value="2"></input>
                        <select  id="col_id2" name="col_id2"  class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku2->col_id }}" >{{ $prod_sku2->col_id }}</option>

                            @foreach ($cols as $col)
                                <option value="{{ $col->id }}" @if(old('col_id1') == $col->id) selected @endif>{{ $col->id }}={{ $col->col_name }}</option>

                            @endforeach
                        </select>

                        <select  id="size_id2" name="size_id2"  class="w-20 h-10 ml-2 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku2->size_id }}"  >{{ $prod_sku2->size_id }}</option>

                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}" @if(old('size_id2') == $size->id) selected @endif>{{ $size->id }}={{ $size->size_name }}</option>

                            @endforeach
                        </select>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="length" value=" 着丈1" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="着丈"  name="length2" value="{{ $prod_sku2->length }}"></input>
                        </div>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="width" value="身幅" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="身幅"  name="width2" value="{{ $prod_sku2->width}}"></input>
                        </div>
                        </div>
                        <input type="file" id="image2" name="image2" accept=“image/png,image/jpeg,image/jpg” class="w-100 md:ml-2 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>


                    <div class="relative">
                        <x-label for="sku3" value="SKU3" class="mt-00"/>
                        <div class="md:flex">
                        <div class="flex">
                        <input type="hidden" class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="sku_id3" value="3"></input>
                        <select  id="col_id3" name="col_id3"  class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku3->col_id }}"  >{{ $prod_sku3->col_id }}</option>

                            @foreach ($cols as $col)
                                <option value="{{ $col->id }}" @if(old('col_id3') == $col->id) selected @endif>{{ $col->id }}={{ $col->col_name }}</option>

                            @endforeach
                        </select>

                        <select  id="size_id3" name="size_id3"  class="w-20 h-10 ml-2 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku3->size_id }}"  >{{ $prod_sku3->size_id }}</option>

                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}" @if(old('size_id3') == $size->id) selected @endif>{{ $size->id }}={{ $size->size_name }}</option>

                            @endforeach
                        </select>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="length" value=" 着丈1" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="着丈"  name="length3" value="{{ $prod_sku3->length }}"></input>
                        </div>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="width" value="身幅" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="身幅"  name="width3" value="{{ $prod_sku3->width}}"></input>
                        </div>
                        </div>
                        <input type="file" id="image3" name="image3" accept=“image/png,image/jpeg,image/jpg” class="w-100 md:ml-2 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>


                    <div class="relative">
                        <x-label for="sku4" value="SKU4" class="mt-00"/>
                        <div class="md:flex">
                        <div class="flex">
                        <input type="hidden" class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="sku_id4" value="4"></input>
                        <select  id="col_id4" name="col_id4"  class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku4->col_id }}" >{{ $prod_sku4->col_id }} </option>

                            @foreach ($cols as $col)
                                <option value="{{ $col->id }}" @if(old('col_id4') == $col->id) selected @endif>{{ $col->id }}={{ $col->col_name }}</option>

                            @endforeach
                        </select>

                        <select  id="size_id4" name="size_id4"  class="w-20 h-10 ml-2 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku4->size_id }}"  >{{ $prod_sku4->size_id }}</option>

                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}" @if(old('size_id4') == $size->id) selected @endif>{{ $size->id }}={{ $size->size_name }}</option>

                            @endforeach
                        </select>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="length" value=" 着丈1" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="着丈"  name="length4" value="{{ $prod_sku4->length }}"></input>
                        </div>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="width" value="身幅" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="身幅"  name="width4" value="{{ $prod_sku4->width}}"></input>
                        </div>
                        </div>
                        <input type="file" id="image4" name="image4" accept=“image/png,image/jpeg,image/jpg” class="w-100 md:ml-2 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>

                    <div class="relative">
                        <x-label for="sku5" value="SKU5" class="mt-00"/>
                        <div class="md:flex">
                        <div class="flex">
                        <input type="hidden" class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="sku_id5" value="5"></input>
                        <select  id="col_id5" name="col_id5"  class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku5->col_id }}" >{{ $prod_sku5->col_id }}</option>

                            @foreach ($cols as $col)
                                <option value="{{ $col->id }}" @if(old('col_id5') == $col->id) selected @endif>{{ $col->id }}={{ $col->col_name }}</option>

                            @endforeach
                        </select>

                        <select  id="size_id5" name="size_id5"  class="w-20 h-10 ml-2 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku5->size_id }}" >{{ $prod_sku5->size_id }}</option>

                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}" @if(old('size_id5') == $size->id) selected @endif>{{ $size->id }}={{ $size->size_name }}</option>

                            @endforeach
                        </select>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="length" value=" 着丈1" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="着丈"  name="length5" value="{{ $prod_sku5->length }}"></input>
                        </div>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="width" value="身幅" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="身幅"  name="width5" value="{{ $prod_sku5->width}}"></input>
                        </div>
                        </div>
                        <input type="file" id="image5" name="image5" accept=“image/png,image/jpeg,image/jpg” class="w-100 md:ml-2 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>

                    <div class="relative">
                        <x-label for="sku6" value="SKU6" class="mt-00"/>
                        <div class="md:flex">
                        <div class="flex">
                        <input type="hidden" class="w-40 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="sku_id6" value="6"></input>
                        <select  id="col_id6" name="col_id6"  class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku6->col_id }}"  >{{ $prod_sku6->col_id }}</option>

                            @foreach ($cols as $col)
                                <option value="{{ $col->id }}" @if(old('col_id6') == $col->id) selected @endif>{{ $col->id }}={{ $col->col_name }}</option>

                            @endforeach
                        </select>

                        <select  id="size_id6" name="size_id6"  class="w-20 h-10 ml-2 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" >
                            <option value="{{ $prod_sku6->size_id }}" >{{ $prod_sku6->size_id }}</option>

                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}" @if(old('size_id6') == $size->id) selected @endif>{{ $size->id }}={{ $size->size_name }}</option>

                            @endforeach
                        </select>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="length" value=" 着丈1" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="着丈"  name="length1" value="{{ $prod_sku6->length }}"></input>
                        </div>
                        <div class="pl-0 mt-0 md:mt-0 ml-2 ">
                            {{-- <x-label for="width" value="身幅" /> --}}
                            <input class="w-20 h-10 bg-gray-100 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="身幅"  name="width1" value="{{ $prod_sku6->width}}"></input>
                        </div>
                        </div>
                        <input type="file" id="image6" name="image6" accept=“image/png,image/jpeg,image/jpg” class="w-100 md:ml-2 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>

                </div>
                </div>

                <div class="px-2 mt-4 mx-auto">
                    <div class="relative flex">

                        @if($prod_sku1->sku_image)
                        <div class="w-80 ml-2">
                            <x-sku_image-thumbnail :filename="$prod_sku1->sku_image" />
                            <span class="text-sm text-center">SKU1</span>
                        </div>
                        @endif
                        @if($prod_sku2->sku_image)
                        <div class="w-80 ml-2">
                            <x-sku_image-thumbnail :filename="$prod_sku2->sku_image" />
                            <span class="text-sm text-center">SKU2</span>
                        </div>
                        @endif

                        @if($prod_sku3->sku_image)
                        <div class="w-80 ml-2">
                            <x-sku_image-thumbnail :filename="$prod_sku3->sku_image" />
                            <span class="text-sm text-center">SKU3</span>
                        </div>
                        @endif
                        @if($prod_sku4->sku_image)
                        <div class="w-80 ml-2">
                            <x-sku_image-thumbnail :filename="$prod_sku4->sku_image" />
                            <span class="text-sm text-center">SKU4</span>
                        </div>
                        @endif
                        @if($prod_sku5->sku_image)
                        <div class="w-80 ml-2">
                            <x-sku_image-thumbnail :filename="$prod_sku5->sku_image" />
                            <span class="text-sm text-center">SKU5</span>
                        </div>
                        @endif
                        @if($prod_sku6->sku_image)
                        <div class="w-80 ml-2">
                            <x-sku_image-thumbnail :filename="$prod_sku6->sku_image" />
                            <span class="text-sm text-center">SKU6</span>
                        </div>
                        @endif
                    </div>
                </div>


                <div class="flex justify-between">

                <div class="p-2 w-1/2 mx-auto flex">
                    <div class="p-2 w-full mt-2 flex justify-around">
                        <button type="submit" class="text-white bg-green-500 border-0 py-2 px-8 focus:outline-none hover:bg-green-600 rounded text-lg">更新</button>
                    </div>
                </div>
                </div>
            </form>
            </div>

        </div>
    </div>



    <script>

        const brand = document.getElementById('brand_id')
        brand.addEventListener('change', function(){
        this.form.submit()
        })

        const unit = document.getElementById('unit_id')
        unit.addEventListener('change', function(){
        this.form.submit()
        })

        const face = document.getElementById('face_code')
        face.addEventListener('change', function(){
        this.form.submit()
        })

        const hinban = document.getElementById('hinban_id')
        hinban.addEventListener('change', function(){
        this.form.submit()
        })






    </script>
</x-app-layout>

