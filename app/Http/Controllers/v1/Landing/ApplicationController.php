<?php

namespace App\Http\Controllers\v1\Landing;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Landing\ApplicationRequest;
use App\Models\v1\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Application::getApplicationsByPeriod($request->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ApplicationRequest $request)
    {
        return Application::addApplication($request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        return Application::where('id', $id)->update([
            'is_active' => 2
        ]);
    }
}
