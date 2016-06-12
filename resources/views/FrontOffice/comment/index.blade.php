@extends('layouts.landing')
@section('htmlheader_title')
    Commentaires - Liste
@stop

@section('main-content')

    <h3>Lites des questions publique</h3>

    <br>

    <div class="row">
        <div class="col-xs-2">
            <h4>Filtrer par</h4>
        </div>
        <div class="col-xs-4">
            <select class="form-control">
                
            </select>
        </div>
        <div class="col-xs-6">
            <a href="{{ url('/comment/question') }}">
                <button class="btn btn-default pull-right">Poser une question</button>
            </a>
        </div>
    </div>

    <br>

    <div id="commentTable">
        {!! $table->getHtmlTable() !!}
    </div>

    <script type="text/javascript">
            
        $(function() {
            // Lors d'un click sur une ligne du tableau des questions
            $('tr').click(function() {
                $data = $(this).find('th').last().html();
                $uri = $data.split(';');
                window.location.href = '/question/' + $uri[0] + '/' + $uri[1];
            });
        });

    </script>

@stop