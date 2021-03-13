<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageReport;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ReportsController extends Controller
{

    /**
     * @function show the "reports" template
     * @param Request $request
     * @return View
     */
    public function show (Request $request): View
    {
        $sortBy = $request->input(['sortBy']) ? $request->input(['sortBy']) : 'created_at';
        $order = $request->input(['order']) ? $request->input(['order']) : 'desc';
        $perPage = $request->input(['perPage']) ? $request->input(['perPage']) : '20';
        $reports = MessageReport::orderBy($sortBy, $order)->paginate($perPage);
        return view('admin.reports.list', compact(
            'reports', 'sortBy', 'order', 'perPage'
        ));
    }

    /**
     * Show and sort+filter reports
     * @param Request $request
     * @return View
     */
    public function sortFilter(Request $request): View
    {
        $formInput = $request->input(['sort']);
        $exploded = explode("--", $formInput);
        $sortBy = $exploded[0];
        $order = $exploded[1];
        $perPage = $request->input(['perPage']);
        $reports = MessageReport::orderBy($sortBy, $order)->paginate($perPage);
        return view('admin.reports.list', compact(
            'reports', 'sortBy', 'order', 'perPage'
        ));
    }

}
