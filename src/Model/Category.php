<?php

namespace Mostafa\Kitchen\Model;

use Mostafa\Kitchen\public\Model;
use Mostafa\Kitchen\View;

class Category extends Model
{
    protected $tableName = 'category_list';

    public function getAllCategories()
    {
        $query = "SELECT * FROM $this->tableName WHERE delete_flag = 0 AND `status` = 1 ORDER BY `name` ASC";
        $this->query($query);
        return $this->resultSet();
    }

    public function getCategory($id)
    {
        $query = "SELECT * FROM $this->tableName WHERE id = $id ";
        $this->query($query);
        return $this->single();
    }

    public function save($category)
    {
        $this->query("INSERT INTO $this->tableName (name, description, status)
         VALUES (:name, :description, :status)");
        $this->bind(':name', $category['name']);
        $this->bind(':description', $category['description']);
        $this->bind(':status', $category['status']);

        if ($this->execute()) {
            return json_encode([
                'status' => 'success',
                'msg' => 'Category has been placed.',
            ]);
        } else {
            return ['success' => false, 'error' => $this->connection->errorInfo()];
        }
    }

    public function update($id,$category){
        $oldCategory = $this->getCategory($id);
        $query = "UPDATE $this->tableName SET name = :name , description = :description , status = :status 
        WHERE id = $oldCategory->id";
        $this->query($query);
        $this->bind(':name', $category['name']);
        $this->bind(':description', $category['description']);
        $this->bind(':status', $category['status']);
        if ($this->execute()) {
            return json_encode([
                'status' => 'success',
                'msg' => 'Category has been updated.',
            ]);
        } else {
            return ['success' => false, 'error' => $this->connection->errorInfo()];
        }
    }
}
