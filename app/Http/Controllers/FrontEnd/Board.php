<?php

namespace App\Livewire\FrontEnd;

use App\Http\Controllers\Controller;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Board extends Controller
{
    public function index($slug =null)
    {
        dd('sdf');
        return view('livewire.front-end.board');
    }

}
