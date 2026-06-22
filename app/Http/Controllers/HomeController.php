<?php

namespace App\Http\Controllers;

use App\ViewModels\HomeViewModel;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'viewModel' => new HomeViewModel(),
        ]);
    }

    public function about(): View
    {
        return view('about');
    }

    public function resources(): View
    {
        return view('resources');
    }
}
