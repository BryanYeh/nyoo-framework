<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\View;
use App\Core\Request;

use App\Models\PersonModel;

class HomeController extends Controller
{
    public function index()
    {
        View::view('index',['name'=>'Bobby Hill']);
    }

    public function database(Request $request)
    {
        $person = (new PersonModel)->where('id',$request->get('id'))->first();
        View::view('database',['person'=>$person]);
    }

    public function create()
    {
        $person = new PersonModel();
        $person->name = 'Nyoo Framework';
        $person->age = rand(1,500);
        $person->save();
        View::view('database',['person'=>$person]);
    }
}