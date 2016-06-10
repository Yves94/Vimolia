@extends('layouts.app')
@section('htmlheader_title')
    Afficher un utilisateur
@stop
@section('contentheader_title')
    Afficher un utilisateur
@stop
@section('main-content')
    <h1>Creer un utilisateur</h1>

    <!-- if there are creation errors, they will show here -->
    {!! Form::open() !!}
    <div class="form-group">
        {{ Form::label('name', 'Nom') }}
        {{ Form::text('name',old('name'),["class"=>"form-control"]) }}
    </div>
    <div class="form-group">
        {{ Form::label('firstname', 'Prénom') }}
        {{ Form::text('firstname',old('firstname'),["class"=>"form-control"]) }}
    </div>
    <div class="form-group">
        {{ Form::label('password', 'Mot de passe') }}
        <input type="password" name="password" id="password" class="form-control">
    </div>
    <div class="form-group">
        {{ Form::label('phone', 'Tèl.') }}
        {{ Form::text('phone',old('phone'),["class"=>"form-control"]) }}
    </div>
    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email',old('email'),["class"=>"form-control"]) }}
    </div>
    <div class="form-group">
        {{ Form::label('user_type', 'Type d\'utilisateur') }}
        {{ Form::select('user_type', array('Public' => 'Publique',
                                        'Pratician' => 'Praticien',
                                        'Expert' => 'Expert'), old('user_type')) }}
    </div>
    <div class="form-group">
        {{ Form::label('is_admin', 'Est admin ?') }}
        {{ Form::checkbox('is_admin',1,old('is_admin')) }}
    </div>
    <!-- public fields -->
    <div class="form-group" name="birthday">
        {!! Form::label('birthday','Anniversaire') !!}
        <div class='input-group date datepicker'>
            {!! Form::text('birthday',old('birthday'), ['class' => 'form-control']) !!}
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <!-- pratician fields -->
    <div class="form-group">
        {{ Form::label('profession', 'Profession') }}
        {{ Form::select('profession', $professions, old('profession')) }}
    </div>
    <div class="form-group">
        {{ Form::label('siret', 'Siret') }}
        {{ Form::text('siret',old('siret'),["class"=>"form-control"]) }}
    </div>
    <div class="form-group">
        {{ Form::label('degree', 'Diplome') }}
        {{ Form::file('degree',null,["class"=>"form-control"]) }}
    </div>

    {{ Form::submit('Créer')  }}
    {!! Form::close() !!}

    <script>
        var publicFields = ['birthday'];
        var praticianFields = ['profession', 'siret', 'degree'];
        var expertFields = [];
        $(document).ready(function () {
            checkCustomField();
            $('select[name=user_type]').on('change', function () {
                checkCustomField();
            });
        });
        //manage hide/show fields
        function checkCustomField() {
            var user_type = $('select[name=user_type]').val();
            switch (user_type) {
                case "Public":
                    showPublicField();
                    hidePraticianField();
                    hideExpertField();
                    break;
                case "Pratician":
                    hidePublicField();
                    showPraticianField();
                    hideExpertField();
                    break;
                case "Expert":
                    hidePublicField();
                    hidePraticianField();
                    showExpertField();
                    break;
            }
        }
        // function for hide or show fields and labels
        function hidePublicField() {
            $.each(publicFields, function (index, value) {
                $('[name=' + value + ']').hide();
                $('label[for=' + value + ']').hide();
            });
        }
        function hidePraticianField() {
            $.each(praticianFields, function (index, value) {
                $('[name=' + value + ']').hide();
                $('label[for=' + value + ']').hide();
            });
        }
        function hideExpertField() {
            $.each(expertFields, function (index, value) {
                $('[name=' + value + ']').hide();
                $('label[for=' + value + ']').hide();
            });
        }
        function showPublicField() {
            $.each(publicFields, function (index, value) {
                $('[name=' + value + ']').show();
                $('label[for=' + value + ']').show();
            });
        }
        function showPraticianField() {
            $.each(praticianFields, function (index, value) {
                $('[name=' + value + ']').show();
                $('label[for=' + value + ']').show();
            });
        }
        function showExpertField() {
            $.each(expertFields, function (index, value) {
                $('[name=' + value + ']').show();
                $('label[for=' + value + ']').show();
            });
        }
    </script>
@stop