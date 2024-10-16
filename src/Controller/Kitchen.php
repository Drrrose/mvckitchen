<?php

namespace Mostafa\Kitchen\Controller;

use Mostafa\Kitchen\Model\Kitchen as kitchen_list;
use Mostafa\Kitchen\View;

class Kitchen {

    private $orderModel;

    public function __construct(){
        $this->orderModel = new kitchen_list() ;
    }
    public function index(){
        View::Render('kitchen.php');
    }
    public function getOrder(){
        // $listed = filter_input_array(INPUT_POST);
        $listed = isset($_POST['listed']) ? $_POST['listed'] : [];
        $orders = $this->orderModel->getOrders($listed);
        echo $orders;
    }
    private function getListedOrderIds() {
        return isset($_POST['listed']) ? $_POST['listed'] : [];
    }
}
