@extends('layouts.game')

@section('content')

    <div
        id="game"
        data-game-id="{{ $game->id }}"
        data-player-id="{{ $selectedPlayer->id }}"></div>

@endsection
