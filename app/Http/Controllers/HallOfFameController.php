<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HallOfFameController extends Controller
{

    /**
     * @function welcome page
     * @param Request $request
     * @return View
     */
    public function index(Request $request):View
    {
        return view('app.halloffame.list');
    }


}
