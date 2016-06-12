@extends('layouts.landing')
@section('htmlheader_title')
    {{ $conversation->title }}
@stop

@section('main-content')

    <h3>{{ $conversation->title }}</h3>

    @foreach ($comments as $comment)

        <div class="boxComment">
            Ecrit par <b>{{ $comment->firstname }} {{ $comment->name }}</b> le <b>{{ date('d/m/Y à H:i:s', strtotime($comment->date)) }}</b><br><br>
            {{ $comment->message }}
        </div>

    @endforeach

    <a href="{{ url('/comment/write/'. $comment->conversation_id) }}">
        <button class="btn btn-default pull-right">Répondre</button>
    </a>

@stop