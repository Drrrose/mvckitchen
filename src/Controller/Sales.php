<?php 

namespace Mostafa\Kitchen\Controller;

use Mostafa\Kitchen\Model\Category;
use Mostafa\Kitchen\Model\Menu;
use Mostafa\Kitchen\Model\Order;
use Mostafa\Kitchen\View;
use Mostafa\Kitchen\public\Request;
use Mostafa\Kitchen\public\Session;

class Sales{

    private $category;
    private $menu;
    private $order;
    private $session;
    public function __construct(){
        $this->category = new Category();
        $this->menu = new Menu();
        $this->order = new Order();
        $this->session = new Session();
    }
    public function showSale(){
        $allCategories = $this->category->getAllCategories();
        $allMenues = $this->menu->allMenues();
        View::Render('sales.php',['allCategories' => $allCategories,'allMenues'=>$allMenues]);
    }
    public function receipt($id)
    {
        $orderLists = $this->order->getOrderLists($id); 
        $orderItem = $this->order->getOrderWithMenu($id); 
        $processed_by = $this->session->getSession('username');
        // Now you have the $id, you can use it to fetch the relevant data and render the receipt.
        View::Render('receipt.php', ['order_lists' => $orderLists,'processed_by'=>$processed_by,'order_item'=>$orderItem]);
    }

    public function makeOrder(){
        $orderData = filter_input_array(INPUT_POST); 
        echo $this->order->placeOrder($orderData);
    }
    public function deleteOrder($id){
        echo $this->order->deleteOrder($id);
    }
}
