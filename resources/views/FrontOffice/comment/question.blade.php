@extends('layouts.landing')
@section('htmlheader_title')
    Commentaires - Question
@stop

@section('main-content')

    @if (isset($response))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="glyphicon glyphicon-ok"></i> Information</h4>
            Votre message à été envoyé avec succès.
        </div>
    @endif

    <div class="row">
        <div class="col-xs-6">
            <h3>Poser une question</h3>
        </div>
        <div class="col-xs-6">
            <a href="{{ url('/comment') }}">
                <button class="btn btn-default pull-right">Revenir à la liste des questions</button>
            </a>
        </div>
    </div>

    @if (Auth::user())

    {{ Form::open(array('url' => 'comment/question')) }}
        
        <div class="form-group">
            {{ Form::label('tag', 'Type de problème') }}
            {{ Form::text('tag', old('tag'), ["class" => "form-control", "required" => true]) }}
        </div>
        <div class="form-group">
            {{ Form::label('subject', 'Sujet') }}
            {{ Form::text('subject', old('subject'), ["class" => "form-control", "required" => true]) }}
        </div>
        <div class="form-group">
            {{ Form::label('message', 'Message') }}
            {{ Form::textarea('message', old('message'), ["class" => "form-control", "rows" => 3, "required" => true]) }}
        </div>

        {{ Form::submit('Envoyer', ["class" => "btn btn-default"])  }}
    {{ Form::close() }}

    @else

        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="glyphicon glyphicon-remove"></i> Connexion requise</h4>
            Veuillez vous connecter pour poser une question
        </div>

    @endif

@stop