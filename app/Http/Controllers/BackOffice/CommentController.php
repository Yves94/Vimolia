<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Requests;
use App\Http\Controllers\Controller as Controller;

class CommentController extends Controller
{
    public function index()
    {
        return view('BackOffice.comment.index');
    }
}