@extends('layouts.app')
@section('htmlheader_title')
    Liste des utilisateurs
@stop
@section('contentheader_title')
    Liste des utilisateurs
@stop


@section('main-content')

    <div class="row">
        <div class="col-md-3">
            {{'Nombre de résultat : '}}{{ $table->getNbResult() }}
        </div>
        <div class="col-md-6" style="text-align: center">
            <span>Tous</span>
            {!! Form::radio('user_type','',empty($type) ? true : false) !!}{{' | '}}
            <span>publique</span>
            {!! Form::radio('user_type','publique',$type=='publique' ? true : false) !!}{{' | '}}
            <span>praticien</span>
            {!! Form::radio('user_type','praticien',$type=='praticien' ? true : false) !!}{{' | '}}
            <span>Expert</span>
            {!! Form::radio('user_type','expert',$type=='expert' ? true : false) !!}
        </div>
        <div class="col-md-3" style="text-align: right">
            {!!$table->getHtmlSearch('Rechercher') . $table->getHtmlSearchReset('Réinitialiser')!!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!!$table->getHtmlTable()!!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="text-align: center">
                {!!$table->getHtmlPagination()!!}
        </div>
    </div>




    <script>
        $(document).ready(function () {
            /**
             * Permet la recherche praticien, admin, publique, tous
             * passe les paremetes en get
             */
            $('input:radio[name=user_type]').on('click', function () {
                var params = getparams();
                params.type = $(this).val();
                window.location.href = currentUrlWithoutParams + generateGetParams(params);
            });

            /**
             * Recupere tous les parametres dans l'url
             * return array
             */
            function getparams() {
                var query = location.search.substr(1);
                var result = {};
                query.split("&").forEach(function (part) {
                    var item = part.split("=");
                    if(decodeURIComponent(item[1]) != "undefined") {
                        result[item[0]] = decodeURIComponent(item[1]);
                    }
                });
                return result;
            }
            /**
             * Genere un string GET
             */
            function generateGetParams(array) {
                var paramsString = '?';
                console.log(array);
                $.each(array, function (key, value) {
                    if (value != undefined && value != '' && value != null && value != false) {
                        paramsString += key + '=' + value + '&';
                    }
                });
                return paramsString.slice(0, -1)
            }
        });


    </script>
@stop

