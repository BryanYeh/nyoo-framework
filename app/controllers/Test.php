<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\View;

use App\Models\Test as TestModel;

class Test extends Controller
{
    public function getTest($test)
    {

        // $db = Database::getInstance();

        // $a = new TestModel();
        // $a->ma();
        // $a->where('id',5)->first();
        // $a->first = 8;
        // $a->save();

        // $a = TestModel::where('id',5)->get();

        // $b = (new TestModel)->where('first',34)->limit(1)->get();
        $a = new TestModel();
        $a->first = 224;
        $a->last = 321;
        $a->date_created = "0d";
        $a->save();
        
        echo "<pre>";
        var_dump($a);
        // var_dump($b);
        echo "</pre>";
        View::view('index');
        // echo $this->twig->render('index');
    }
}
