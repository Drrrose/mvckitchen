<?php

namespace Mostafa\Kitchen\Controller;

use Mostafa\Kitchen\Model\Category as categoryList;
use Mostafa\Kitchen\View;

class Category
{
    private $category;
    public function __construct()
    {
        $this->category = new categoryList();
    }
    public function allCategories()
    {
        $allCategories =  $this->category->getAllCategories();
        View::Render('category.php', ['allCategories' => $allCategories]);
    }

    public function viewCategory($id)
    {
        $category =  $this->category->getCategory($id);
        View::Render('viewCategory.php', ['category' => $category]);
    }

    public function makeCategory(){
        View::Render('makeCategory.php');
    }
    public function editCategory($id){
        $category =  $this->category->getCategory($id);
        View::Render('editCategory.php',['category' => $category]);
    }

    public function updateCategory($id){
        $_POST = filter_input_array(INPUT_POST); 
        echo $this->category->update($id,$_POST);
    }

    public function saveCategory(){
        $_POST = filter_input_array(INPUT_POST); 
        $category = [
            "name" => $_POST['name'],
            "description" => $_POST['description'],
            "status" => $_POST['status'],
        ];
        echo $this->category->save($category);
    }
}
