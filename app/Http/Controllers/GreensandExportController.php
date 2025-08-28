<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\GreensandExportFull;
use Maatwebsite\Excel\Facades\Excel;

class GreensandExportController extends Controller
{
    public function download(Request $request)
    {
        $export = new GreensandExportFull(
            start: $request->query('start'),
            end:   $request->query('end'),
            shift: $request->query('shift'),
            q:     $request->query('q'),
            mm:    $request->query('mm') ? (int)$request->query('mm') : null, // 1 | 2 | null
        );

        return Excel::download($export, 'greensand_'.now()->format('Ymd_His').'.xlsx');
    }
}
