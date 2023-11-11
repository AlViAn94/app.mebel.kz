<?php

namespace App\Http\Controllers\v1\Order;

use App\Http\Controllers\Controller;
use App\Models\v1\Comment;
use Illuminate\Http\Request;

class OrderCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Comment::list($request->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        return Comment::addComment($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Comment::deletedComment($id);
    }
}
