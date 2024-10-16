<?php 

namespace Mostafa\Kitchen\Model;
use Mostafa\Kitchen\public\Model;

class Menu extends Model{
    protected $tableName= 'menu_list';

    public function allMenues(){
        $query = "SELECT * FROM $this->tableName WHERE delete_flag = 0 AND `status` = 1 ORDER BY `name` ASC";
        $this->query($query);
        return $this->resultSet();
    }
}