<?php

namespace App\Http\Controllers;

use App\Console\Commands\ImportCsvCities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class AdminController extends Controller
{
    /**
     * Import cities in DB.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importCities(Request $request) {
        return redirect()->route('home');
    }


    public function retrievexml(Request $request){
        $path = 'http://site.com/export.xml';
        $filename = 'export.xml';

        $tempXML = tempnam(sys_get_temp_dir(), $filename);
        copy($path, $tempXML);

        return response()->download($tempXML, $filename);
    }
}
