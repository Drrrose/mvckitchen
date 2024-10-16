<?php

namespace Mostafa\Kitchen\Model;

use Mostafa\Kitchen\public\Model;
use Mostafa\Kitchen\public\Session;

class Order extends Model
{
    protected $tableName = 'order_list';
    private $session;

    public function __construct(){
        parent::__construct();
        $this->session = new Session();
    }
    public function placeOrder($orderData)
    {
        $orderData['code'] = $this->generateUniqueCode();
        $orderData['queue'] = $this->extractQueueCode($orderData['code']);
        $orderData['user_id'] = $this->session->getSession('user_id');

        $orderInsertionResult = $this->insertOrder($orderData);

        if ($orderInsertionResult['success']) {
            $orderId = $orderInsertionResult['order_id'];
            $orderItemsInsertionResult = $this->insertOrderItems($orderId, $orderData);

            if ($orderItemsInsertionResult['success']) {
                return json_encode([
                    'status' => 'success',
                    'msg' => 'Order has been placed.',
                    'order_id' =>  $orderId
                ]);
            } else {
                echo $this->rollbackOrder($orderId);
                return json_encode([
                    'status' => 'failed',
                    'msg' => 'Order items could not be saved.',
                    'err' => $orderItemsInsertionResult['error'],
                    'sql' => $orderItemsInsertionResult['sql']
                ]);
            }
        } else {
            return json_encode([
                'status' => 'failed',
                'msg' => 'Order could not be saved.',
                'err' => $orderInsertionResult['error'],
                'sql' => $orderInsertionResult['sql']
            ]);
        }
    }
    // Insert the order into the database
    private function insertOrder($orderData)
    {
        // Define the fields that are allowed to be inserted
        $orderFields = ['code', 'queue', 'total_amount', 'tendered_amount', 'user_id'];
        
        // Prepare the field names and values
        $fields = [];
        $placeholders = [];
        
        foreach ($orderData as $k => $v) {
            if (in_array($k, $orderFields) && !is_array($v)) {
                $fields[] = "`{$k}`";
                $placeholders[] = ":{$k}";
            }
        }
        
        // Construct the SQL statement with placeholders
        $sql = "INSERT INTO `{$this->tableName}` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        // Prepare the statement
        $this->query($sql);
        
        // Bind the parameters
        foreach ($orderData as $k => $v) {
            if (in_array($k, $orderFields) && !is_array($v)) {
                $this->bind(':'.$k, $v);
            }
        }
        
        // Execute the statement
        $success = $this->execute();
        
        // Return result
        if ($success) {
            return ['success' => true, 'order_id' => $this->connection->lastInsertId()];
        } else {
            return ['success' => false, 'error' => $this->connection->errorInfo(), 'sql' => $sql];
        }
    }
    // Insert order items into the database
    private function insertOrderItems($orderId, $orderData)
    {
        $data = '';
        foreach ($orderData['menu_id'] as $k => $v) {
            if (!empty($data)) $data .= ", ";
            $data .= "('{$orderId}', '{$orderData['menu_id'][$k]}', '{$orderData['price'][$k]}', '{$orderData['quantity'][$k]}')";
        }

        $sql = "INSERT INTO `order_items` (`order_id`, `menu_id`, `price`, `quantity`) VALUES {$data}";
        $this->query($sql);
        $success = $this->execute();

        if ($success) {
            return ['success' => true];
        } else {
            return ['success' => false, 'error' => $this->connection->errorInfo(), 'sql' => $sql];
        }
    }

    // Rollback order if there is an issue with inserting order items
    private function rollbackOrder($orderId)
    {
        $this->query("DELETE FROM `{$this->tableName}` WHERE id = :id");
        $this->bind(':id', $orderId);
        return $this->execute();
    }

    public function getOrderLists($id){
        $query = "SELECT * FROM $this->tableName WHERE id = $id";
        $this->query($query);
        return $this->single();
    }

    public function getOrderWithMenu($id){
       $query =" SELECT oi.*, concat(m.code,' - ', m.name) as `item` FROM `order_items` oi inner join `menu_list` m on oi.menu_id = m.id where oi.order_id = '{$id}'";
       $this->query($query);
       return $this->single();
    }
    public function allorderes()
    {
        $query = "SELECT * FROM $this->tableName WHERE delete_flag = 0 AND `status` = 1 ORDER BY `name` ASC";
        $this->query($query);
        return $this->resultSet();
    }

    public function getAllOrdersByUser(){
        $user_id = $this->session->getSession('user_id');
        $query = "SELECT * FROM `order_list` WHERE user_id = $user_id  order by date_created desc ";
        $this->query($query);
        return $this->resultSet();
        
    }

    public function deleteOrder($id){
        if($this->rollbackOrder($id)){
        	$resp['status'] = 'success';
        	$resp['msg'] = 'Order has been deleted successfully.';
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->connection->errorInfo();
            
		}
		return json_encode( $resp);
    }
        // Generate a unique code for the order
        private function generateUniqueCode()
        {
            $prefix = date("Ymd");
            $code = sprintf("%'.05d", 1);
    
            while (true) {
               $this->query("SELECT * FROM `order_list` where code = '{$prefix}{$code}'");
                $this->resultSet();
                $check = $this->rowCount();
                if ($check > 0) {
                    $code = sprintf("%'.05d", abs($code) + 1);
                } else {
                    return $prefix . $code;
                }
            }
        }
    
        // Extract the queue code from the full unique code
        private function extractQueueCode($fullCode)
        {
            return substr($fullCode, -5); // Adjust based on your code format
        }
}
