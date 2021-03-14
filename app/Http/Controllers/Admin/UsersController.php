<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suspension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;


class UsersController extends Controller
{
    /**
     * Show all users
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request): \Illuminate\View\View
    {
        $sortBy = $request->input(['sortBy']) ? $request->input(['sortBy']) : 'created_at';
        $order = $request->input(['order']) ? $request->input(['order']) : 'desc';
        $perPage = $request->input(['perPage']) ? $request->input(['perPage']) : '20';
        $query = DB::Table('users');
        $filterEmail = $request->input(['filterEmail']);
        $filterRole = $request->input(['filterRole']);
        $filterId = $request->input(['filterId']);
        if (!is_null($filterEmail)) {
            $query = $query->where('email', 'like', '%'.$filterEmail.'%');
        }
        if (!is_null($filterRole)) {
            $query = $query->where('role', $filterRole);
        }
        if (!is_null($filterId)) {
            $query = $query->where('id', $filterId);
        }
        $users = $query->orderBy($sortBy, $order)->paginate($perPage);
        $suspensions = [];
        foreach (Suspension::all() as $suspension) {
            if ($suspension->isActive() && !in_array($suspension->user_id, $suspensions)) {
                $suspensions[] = $suspension->user_id;
            }
        }
        return View::make('admin.users.list', compact(
            'users', 'sortBy', 'order', 'perPage', 'filterEmail', 'filterRole', 'filterId', 'suspensions'
        ));
    }


    /**
     * change sort oder and/or filter.
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function sortFilter(Request $request): \Illuminate\View\View
    {
        $formInput = $request->input(['sort']);
        $exploded = explode("--", $formInput);
        $sortBy = $exploded[0];
        $order = $exploded[1];
        $perPage = $request->input(['perPage']);
        $query = DB::Table('users');
        $filterEmail = $request->input(['filterEmail']);
        $filterRole = $request->input(['filterRole']);
        $filterId = $request->input(['filterId']);
        if (!is_null($filterEmail)) {
            $query = $query->where('email', 'like', '%'.$filterEmail.'%');
        }
        if (!is_null($filterRole)) {
            $query = $query->where('role', $filterRole);
        }
        if (!is_null($filterId)) {
            $query = $query->where('id', $filterId);
        }
        $users = $query->orderBy($sortBy, $order)->paginate($perPage);
        $suspensions = [];
        foreach (Suspension::all() as $suspension) {
            if ($suspension->isActive() && !in_array($suspension->user_id, $suspensions)) {
                $suspensions[] = $suspension->user_id;
            }
        }
        return View::make('admin.users.list', compact(
            'users', 'sortBy', 'order', 'perPage', 'filterEmail', 'filterRole', 'filterId', 'suspensions'
        ));
    }

}
