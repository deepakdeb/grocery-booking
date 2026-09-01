<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function index(): View
    {
        return view('orders.index');
    }
}
