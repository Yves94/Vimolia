<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Requests;
use App\Http\Controllers\Controller as Controller;
use App\Comment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Laraveltable\Table\Table;
use Carbon\Carbon;

class CommentController extends Controller
{
    public function index()
    {
        $table = new Table('conversations');
        $table->setColumnDisplayed([ 'title' => 'Sujet', 'date' => 'Dernier message le ', 'conversation_id' => 'ID' ]);
        $table->addJoin(['comments', 'comments.conversation_id', '=', 'conversations.id']);
        $table->addJoin(['comments_opinions', 'comments_opinions.comment_id', '=', 'comments.id']);
        $table->addWhere(['is_public', '=', '0']);
        $table->setColumnSorted([]);
        $table->addCallBackData('date', function ($data) {
            if(!empty($data)) {
                $carbon = new Carbon($data);
                $data = $carbon->format('d/m/Y à H:i:s');
            }
            return $data;
        });
        $table->addCallBackData('conversation_id', function($data) {
            $getSlugById = DB::table('conversations')->where('conversations.id', '=', $data)->first();
            return $data .';'. $getSlugById->slug;
            // $getCommentsByConversation = DB::table('comments')->join('conversations', 'comments.conversation_id', '=', 'conversations.id')->where('conversations.id', '=', $data)->get();
            // return count($getCommentsByConversation) - 1;
        });
        /*$table->addCallBackData('id', function($data) {
            $getIdConversation = DB::table('conversations')->join('comments', 'comments.conversation_id', '=', 'conversations.id')->get();
            return $getIdConversation->id;
        });*/
        $table->prepareView();
        $data['table'] = $table;

        return view('FrontOffice.comment.index', $data);
    }

    public function question()
    {
        return view('FrontOffice.comment.question');
    }

    public function saveQuestion(Request $request)
    {
        $subject = $request->input('subject');
        $message = $request->input('message');
        $tag = $request->input('tag');
        $user = Auth::user();

        $slug = str_slug($subject, '-');

        // Ajout du commentaire à une conversation
        $conversationId =  DB::table('conversations')->insertGetId(
            ['title' => $subject, 'slug' => $slug, 'tag' => $tag ]
        );

        // Ajout du commentaire en base de données
        DB::table('comments')->insert(
            ['message' => $message, 'user_id' => $user->id, 'conversation_id' => $conversationId ]
        );

        $data['response'] = 'Success';
        return view('FrontOffice.comment.question', $data);
    }

    public function showConversation(Request $request)
    {
        $conversationId = $request->segment(2);
        $conversationSubject = $request->segment(3);

        $data['conversation'] = DB::table('conversations')->where('conversations.id', '=', $conversationId)->first();
        $data['comments'] = DB::table('comments')->join('conversations', 'comments.conversation_id', '=', 'conversations.id')->join('users', 'users.id', '=', 'comments.user_id')->where('conversations.id', '=', $conversationId)->get();
        
        return view('FrontOffice.comment.conversation', $data);
    }
}