<?php

namespace Mostafa\Kitchen\Controller;

use Mostafa\Kitchen\Model\Order as orderList;
use Mostafa\Kitchen\View;

class Order {
    private $order;
    public function __construct(){
        $this->order = new orderList();
    }
    public function allOrders(){
       $allOrders =  $this->order->getAllOrdersByUser();
       View::Render('orders.php',['allOrders' => $allOrders]);
    }
}
