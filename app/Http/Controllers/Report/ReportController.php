<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;

use App\Models\Transaction;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Transaction::with('category')->get();

        return view('pages.report.report', compact('reports'));
    }
}