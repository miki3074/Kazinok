<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class DepositsController extends Controller
{
    public function index()
    {
        return view('admin.deposits.index');
    }
}
