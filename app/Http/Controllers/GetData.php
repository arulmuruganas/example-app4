<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pizza;

class GetData extends Controller
{
    public function  sayhello(){
        return "Hello World GetData";
    }

    public function controlexample(){
        return "Hello World GetData controlexample";
    }

    public function get_db_data(){
        print"Hi2";
        $pizzas = Pizza::all();
        var_dump($pizzas);
    }
}
