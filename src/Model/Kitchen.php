<?php

namespace Mostafa\Kitchen\Model;

use Mostafa\Kitchen\public\Model;

class Kitchen extends Model
{
    public function getOrders($listed)
{
    $swhere = "";
    if (isset($listed) && count($listed) > 0) {
        $swhere = " and id not in (".implode(",",$listed).")";
    }
    $query = "SELECT id, `queue` FROM `order_list` where `status` = 0 {$swhere}
    order by abs(unix_timestamp(date_created)) asc limit 10";
    $this->query($query);
    $orders = $this->resultSet(); // Change this to fetch all results

    $data = [];
    foreach ($orders as $row) {
        $query2 = "SELECT oi.*, concat(m.code, m.name) as `item` FROM `order_items` oi inner join menu_list m on oi.menu_id = m.id where order_id = '{$row->id}'";
        $this->query($query2);
        $items = $this->resultSet(); // Change this to fetch all results
        $item_arr = [];
        foreach ($items as $irow) {
            $item_arr[] = $irow;
        }
        $row->item_arr = $item_arr;
        $data[] = $row;
    }

    $resp = [
        'status' => 'success',
        'data' => $data
    ];
    return json_encode($resp); // Return the response after processing all orders
}

    
}
