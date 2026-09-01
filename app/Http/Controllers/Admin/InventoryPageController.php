<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class InventoryPageController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.index');
    }

    public function index(): View
    {
        return view('admin.items.index');
    }

    public function create(): View
    {
        return view('admin.items.form');
    }

    public function edit(int $id): View
    {
        return view('admin.items.form');
    }
}
