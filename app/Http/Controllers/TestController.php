<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function indexTest()
    {
        return response()->json(['message' => 'Swagger works']);
    }
}
