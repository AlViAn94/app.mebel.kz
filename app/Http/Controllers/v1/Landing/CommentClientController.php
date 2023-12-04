<?php

namespace App\Http\Controllers\v1\Landing;

use App\Http\Controllers\Controller;
use App\Services\v1\Landing\ClientCommentService;
use Illuminate\Http\Request;

class CommentClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function index()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, ClientCommentService $service)
    {
        return $service->addComment($request);
    }

//    public function destroy($id)
//    {
//        //
//    }
}
