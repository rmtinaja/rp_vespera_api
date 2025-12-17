<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class BillingController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Swagger works']);
    }
    public function index1()
    {
        return response()->json(['message' => 'Swagger works']);
    }
    public function index2()
    {
        return response()->json(['message' => 'Swagger works']);
    }
}