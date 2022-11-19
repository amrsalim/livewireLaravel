<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProductInput extends Component
{

    public $name,
        $qty, $item_id,
        $price_before_discount,
        $net, $discount_percentage, $discount_amount,
        $item_discount, $value_difference, $ProductList, $total_price, $price, $sales;
    public $t1_percentage = 0.14;
    public $t2_percentage = 0;
    public $t3_percentage = 5;
    public $t4_percentage = 0.01;
    public $t5_percentage = 5;
    public $t6_percentage = 0;
    public $t7_percentage = 0;
    public $t8_percentage = 0;
    public $t9_percentage = 0;
    public $t10_percentage = 0;
    public $t11_percentage = 0;
    public $t12_percentage = 0;
    public $t13_percentage = 0;
    public $t14_percentage = 0;
    public $t15_percentage = 0;
    public $t16_percentage = 0;
    public $t17_percentage = 0;
    public $t18_percentage = 0;
    public $t19_percentage = 0;
    public $t20_percentage = 0;

    public $taxes = [];
    public function mount()
    {
        $newRow = [
            'name' => "",
            'qty' => 1,
            'price_before_discount' => 0,
            'net' => 0,
            'discount_percentage' => 0,
            'item_discount' => 0,
            'value_difference' => 0,
            'item_id' => 1,
            'price' => 0,
            'sales' => 0,
            'discount_amount' => 0,
            "texes" => []

        ];
        $this->ProductList[] = $newRow;
        $this->total_price = 0;
    }

    public function render()
    {
        $ProductList = $this->ProductList;
        return view('livewire.product-input', compact('ProductList'));
    }

    public function showModel($index) {
        $this->taxes =  $this->ProductList[$index]['texes'] ?? [];
        $this->dispatchBrowserEvent("show-texes" , $this->ProductList[$index]);
    }
    public function closeModel() {
        $this->dispatchBrowserEvent("close-texes-model");
    }


    public function gettotal($index)
    {
        $this->ProductList[$index]['sales'] = (int)$this->ProductList[$index]['price'] * (int)$this->ProductList[$index]['qty'];
    }

    public function calculateDescountPercentage($index)
    {
        $value = (int)$this->ProductList[$index]['discount_percentage'] / 100;
        $total = (int)$this->ProductList[$index]['sales'];
        $this->ProductList[$index]['net'] = $total - ((int)$this->ProductList[$index]['sales'] * $value);
        $this->ProductList[$index]['price_before_discount'] = $total;
    }

    public function CalculateDescountAmount($index)
    {
        $value = (int)$this->ProductList[$index]['discount_amount'];
        $total = (int)$this->ProductList[$index]['sales'];
        $this->ProductList[$index]['net'] = $total - $value;
        $this->ProductList[$index]['price_before_discount'] = $total;
    }

    public function AddNewRow()
    {
        $this->resetInputFields();
        $Listitem = end($this->ProductList);
        $newRow = [
            'name' => "",
            'qty' => 1,
            'price_before_discount' => 0,
            'net' => 0,
            'discount_percentage' => 0,
            'item_discount' => 0,
            'value_difference' => 0,
            'price' => 0,
            'sales' => 0,
            'discount_amount' => 0,
            'item_id' => ($Listitem['item_id'] + 1),
            "texes" => []

        ];

        $this->ProductList[] = $newRow;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->qty = '';
        $this->price_before_discount = '';
        $this->net = '';
        $this->discount_percentage = '';
        $this->value_difference = '';
        $this->item_discount = '';
        $this->item_id = '';
        $this->price = '';
        $this->sales = '';
        $this->discount_amount = '';
    }

    public function pushToListProduct()
    {
        $newRow = [
            'name' => $this->name,
            'qty' => $this->qty,
            'price_before_discount' => $this->price_before_discount,
            'net' => $this->net,
            'discount_percentage' => $this->discount_percentage,
            'item_discount' => $this->item_discount,
            'value_difference' => $this->value_difference,
            'item_id' => $this->item_id,
            'price' => $this->price,
            'sales' => $this->sales,
            'discount_amount' => $this->discount_amount,
            'texes' => []
        ];
        $this->ProductList[] = $newRow;
        $this->addTaxesList();

        $this->resetInputFields();
        $this->getTotalPrice();
    }

    private function addTaxesList()
    {
        foreach ($this->ProductList as $index => $item) {
            if ($item['name'] != null || $item['name'] != "") {

                $new = [
                    [
                        'name' => "t1",
                        'sub_type' => "v001",
                        'amount' => $this->calculate_t1($index),
                        'rate' => $this->t1_percentage,
                    ],
                    [
                        'name' => "t2",
                        'sub_type' => "v002",
                        'amount' => $this->calculate_t2($index),
                        'rate' => $this->t2_percentage,
                    ],
                    [
                        'name' => "t3",
                        'sub_type' => "v003",
                        'amount' => $this->t3_percentage,
                        'rate' => $this->t3_percentage,
                    ],
                    [
                        'name' => "t4",
                        'sub_type' => "v004",
                        'amount' => $this->calculate_t4($index),
                        'rate' => $this->t4_percentage,
                    ],
                ];
                for ($i = 5; $i <= 20; $i++) {
                    $data = [
                        'name' => "t$i",
                        'sub_type' => "v005",
                        'amount' => $this->calculate_t5_to_t20($this->item_id, "t" . $i . "_percentage"),
                        'rate' => $this->t5_percentage,
                    ];
                    array_push($new, $data);
                }
                $this->ProductList[$index]['texes'] = $new;

            }
        }
    }

    private function calculate_t1($index): int
    {
//        dd($index, $this->ProductList);
        $net = (int)$this->ProductList[$index]['net'];
        $value_difference = (int)$this->ProductList[$index]['value_difference'];
        return ($net + $value_difference + $this->total_taxable_amount($index) + $this->t3_percentage + $this->calculate_t2($index)) * $this->t1_percentage;
    }

    // t1  (net + value_difference + total_taxable_amount + t3 + t2) * t1

    private function total_taxable_amount($index): int
    {
        $total = 0;
        for ($i = 5; $i <= 12; $i++) {
            $total += $this->calculate_t5_to_t20($index, "t" . $i . "_percentage");
        }
        return $total;
    }

    // t2  (net + value_difference + total_taxable_amount + t3) * t2

    private function calculate_t5_to_t20($index, $t): int
    {
        $net = $this->ProductList[$index]['net'] ?? 0;
        return $net * $this->$t;
    }

    // t4

    private function calculate_t2($index): int
    {
        $net = (int)$this->ProductList[$index]['net'];
        $value_difference = (int)$this->ProductList[$index]['value_difference'];
        return ($net + $value_difference + $this->total_taxable_amount($index) + $this->t3_percentage) * $this->t2_percentage;
    }

    // t5 to t20

    private function calculate_t4($index): int
    {
        $net = (int)$this->ProductList[$index]['net'];
        $item_discount = (int)$this->ProductList[$index]['item_discount'];
        return ($net - $item_discount) * $this->t4_percentage;
    }

    // sum of t5 => t12

    private function getTotalPrice()
    {
        $this->total_price = 0;
        foreach ($this->ProductList as $item) {
            $this->total_price += (int)$item['net'];
        }
    }

    // sum of t13 => t20

    public function RemoveProduct($index)
    {
        unset($this->ProductList[$index]);
    }

    // add texes

    private function total_non_taxable_amount($index): int
    {
        $total = 0;
        for ($i = 13; $i <= 20; $i++) {
            $total += $this->calculate_t5_to_t20($index, "t" . $i . "_percentage");
        }
        return $total;
    }
}
