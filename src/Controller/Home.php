<?php 

namespace Mostafa\Kitchen\Controller;

use Mostafa\Kitchen\View;

class Home{ 
    public function index(){
        View::Render('home.php');
    }
}