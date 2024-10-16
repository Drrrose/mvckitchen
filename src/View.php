<?php 

namespace Mostafa\Kitchen;

class View{
    public static function Render($fileName,$data=[]){
        $viewFile= __DIR__ .'\View\\' . $fileName;
        if(file_exists($viewFile)){
            include $viewFile;
        }else {
            echo "not exist";
        }
    }
}
