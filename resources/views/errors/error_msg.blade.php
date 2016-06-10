@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="card">
            <div class="card-content">
                <div class="card-title">エラー</div>
                <p>{{$message or ''}}</p>
                <a href="/" class="btn waves-effect">ホームへ戻る</a>
            </div>
        </div>
    </div>
@endsection