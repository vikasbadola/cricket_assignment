<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Match;

class MatchController extends Controller 
{
    /**
     * @created on - 07/17/2020
     * @created by - Vikas Badola
     * @param Request $request
     * @desc - Show points list page
     */
    public function index(Request $request) {
        if (request()->ajax()) {
            return datatables()->of(Match::with(['teamA','teamB','winner'])->latest()->get())
            ->make(true);
        }
        return view('matchList');
    }
}