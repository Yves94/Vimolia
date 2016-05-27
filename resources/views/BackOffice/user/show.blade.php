@extends('layouts.app')
@section('htmlheader_title')
    Afficher un utilisateur
@stop
@section('contentheader_title')
    Afficher un utilisateur
@stop
@section('main-content')
    {{'Utilisateur : '}}
    {{ $user->civility }}{{ $user->name }}{{ $user->firstname }}<br>
    {{'Email : '}}
    {{ $user->email }}<br>
    @if ($user->phone != null)
        {{'Tel. : '}}{{ $user->phone }}<br>
    @endif
    {{'Status de l\'utilisateur : '}}{{ $user->getUserableTypeReadable() }}<br>
    {{-- Si c'est un utilisateur publique on affiche ses attributs spécifiques --}}
    @if($user->isValidBirthday())
        {{'Date d\'anniversaire : '}} {{ date('d-m-Y', strtotime($user->userable->birthday)) }}<br>
    @endif
    @if ($address != null)
        {{'Adresse :'}}
        {{ $address->address }}
        {{ $address->postal_code }}
        {{ $address->city }}
    @endif
@stop