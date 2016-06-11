@extends('layouts.landing')
@section('htmlheader_title')
    Commentaires
@stop

@section('main-content')

    @if (isset($response))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="glyphicon glyphicon-ok"></i> Information</h4>
            Votre message à été envoyé avec succès.
        </div>
    @endif

    <h3>Poser une question</h3>

    {{ Form::open(array('url' => 'comment')) }}
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

@stop