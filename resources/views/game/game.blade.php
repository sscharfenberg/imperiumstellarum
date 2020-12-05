@extends('layouts.game')

@section('content')
    <script type="text/javascript">
        window.rules = @json(config('rules'))
    </script>
    <div
        id="game"
        data-game-id="{{ $gameId }}"
        data-game-number="{{ $gameNumber }}"></div>
@endsection
