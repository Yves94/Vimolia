<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Requests;
use App\Http\Controllers\Controller as Controller;
use App\Comment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        return view('FrontOffice.comment.index');
    }

    public function saveQuestion(Request $request)
    {
        $subject = $request->input('subject');
        $message = $request->input('message');
        $user = Auth::user();

        // Ajout du commentaire en base de données
        $commentId = DB::table('comments')->insertGetId(
            ['message' => $message, 'user_id' => $user->id ]
        );

        // Ajout du commentaire à une conversation
        DB::table('conversations')->insert(
            ['title' => $subject, 'comment_id' => $commentId ]
        );

        $data['response'] = 'Success';
        return view('FrontOffice.comment.index', $data);
    }
}