<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DimensionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dimensions = getenv("DIMENSIONS");
        $dimensions_array = explode(";", $dimensions);
        array_unshift($dimensions_array, "original");

        return response()->json($dimensions_array);
    }
}
