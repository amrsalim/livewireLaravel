<div class="table-responsive">
    <table class="table table-bordered  mt-5">
        <thead>
        <tr>
            <th scope="col">الرقم</th>
            <th scope="col">الاسم</th>
            <th scope="col">السعر</th>
            <th scope="col" style="width: 85px">الكمية</th>
            <th scope="col" style="width: 85px">الاجمالى</th>
            <th scope="col" style="width: 85px">نسبة الخصم</th>
            <th scope="col" style="width: 85px">قيمة الخصم</th>
            {{--            <th scope="col" style="width: 85px">السعر قبل الخصم</th>--}}
            <th scope="col" style="width: 85px">السعر بعد الخصم</th>
            <th scope="col" style="width: 85px">فرق الخصم</th>
            <th scope="col" style="width: 85px">الخصم الاضافى</th>
            <th>الاعدادات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ProductList as $index=>$product)
            <tr>
                <td><input class="form-control" style="width: 85px" name="ProductList[{{$index}}]['item_id']"
                           value="{{$product['item_id']}}"
                           wire:model="ProductList.{{$index}}.item_id"></td>
                <td><input class="form-control" style="width: 150px;" name="ProductList[{{$index}}]['name']"
                           value="{{$product['name']}}"
                           wire:model="ProductList.{{$index}}.name"></td>
                <td><input class="form-control" style="width: 85px;" name="ProductList[{{$index}}]['price']"
                           value="{{$product['price']}}"
                           wire:model="ProductList.{{$index}}.price" wire:change="gettotal({{$index}})"></td>
                <td><input class="form-control" style="width: 85px" name="ProductList[{{$index}}]['qty']"
                           value="{{$product['qty']}}"
                           wire:model="ProductList.{{$index}}.qty" wire:change="gettotal({{$index}})"></td>
                <td><input class="form-control" style="width: 85px" name="ProductList[{{$index}}]['sales']"
                           value="{{$product['sales']}}"
                           wire:model="ProductList.{{$index}}.sales"></td>
                <td><input class="form-control" style="width: 85px"
                           name="ProductList[{{$index}}]['discount_percentage']"
                           value="{{$product['discount_percentage']}}"
                           wire:model="ProductList.{{$index}}.discount_percentage"
                           wire:change="calculateDescountPercentage({{$index}})"></td>
                <td><input class="form-control" style="width: 85px"
                           name="ProductList[{{$index}}]['discount_amount']"
                           value="{{$product['discount_amount']}}"
                           wire:model="ProductList.{{$index}}.discount_amount"
                           wire:change="CalculateDescountAmount({{$index}})"></td>

                {{--                <td><input class="form-control" style="width: 85px"--}}
                {{--                           name="ProductList[{{$index}}]['price_before_discount']"--}}
                {{--                           value="{{$product['price_before_discount']}} "--}}
                {{--                           wire:model="ProductList.{{$index}}.price_before_discount"></td>--}}
                <td><input class="form-control" style="width: 85px"
                           name="ProductList[{{$index}}]['net']"
                           value="{{$product['net']}}"
                           wire:model="ProductList.{{$index}}.net"></td>
                <td><input class="form-control" style="width: 85px" name="ProductList[{{$index}}]['value_difference']"
                           value="{{$product['value_difference']}}"
                           wire:model="ProductList.{{$index}}.value_difference"></td>
                <td><input class="form-control" style="width: 85px" name="ProductList[{{$index}}]['item_discount']"
                           value="{{$product['item_discount']}}"
                           wire:model="ProductList.{{$index}}.item_discount"></td>
                <td>
                    {{--                    <button onclick="Livewire.emit('openModal', 'show-tax')">Show Tax</button>--}}
                    {{--                    <button onclick='Livewire.emit("openModal", "show-tax", {{ json_encode(["ProductList" => $ProductList , "index"=>$index ]) }})'>Edit User</button>--}}

                    {{--                    data-toggle="modal" data-target="#ShowTexes"--}}
                    <button class="btn btn-danger btn-sm" wire:click="showModel({{$index}})">show Tex</button>
                    <button wire:click="RemoveProduct({{$index}} )" class="btn btn-danger btn-sm">Delete</button>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    <button wire:click="pushToListProduct()" class="btn btn-primary btn-sm">save</button>

    <button wire:click="AddNewRow()" class="btn btn-primary btn-sm">Add Item</button>


    <h1>total price invoice Line : {{$total_price}}</h1>


    <!-- Modal -->
    <div class="modal fade" wire:ignore.self id="texes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">عرض الضريبة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">name</th>
                            <th scope="col">sub name</th>
                            <th scope="col">amount</th>
                            <th scope="col" style="width: 85px">rate</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($this->taxes as $item)

                            <tr>
                                <td>{{$item['name']}}</td>
                                <td>{{$item['sub_type']}}</td>
                                <td>{{$item['amount']}}</td>
                                <td>{{$item['rate']}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModel()">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>

@push("script")
    <script>
        window.addEventListener('close-texes-model', event => {
            $('#texes').modal("hide");
            $('.modal-backdrop').remove();
        });
        window.addEventListener('show-texes', event => {
            $('#texes').modal('show');
            $('#texes .modal-body').modal('show');

        })
    </script>
@endpush
