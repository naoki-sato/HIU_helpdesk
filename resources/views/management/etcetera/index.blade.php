@extends('layouts.app')

@section('css')
@endsection

@section('content')

    {{-- パンくずリスト --}}
    <div>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}">Top</a></li>
            <li class="active">etc.</li>
        </ol>
    </div>

        {{-- link --}}
        <div class="col-md-8 col-md-offset-2">
            <ul class="list-group">
                <li class="list-group-item active"><i class="glyphicon glyphicon-link"></i> Link</li>
                <a class="list-group-item" href="https://groups.google.com/a/s.do-johodai.ac.jp/forum/#!forum/helpdesk" target="_blank"><i class="glyphicon glyphicon-triangle-right"></i> Helpdesk Google Group</a>
                <a class="list-group-item" href="{{url('/wikipedia')}}" target="_blank"><i class="glyphicon glyphicon-triangle-right"></i> Helpdesk Wikipedia</a>
                <a class="list-group-item" href="http://account.do-johodai.ac.jp" target="_blank"><i class="glyphicon glyphicon-triangle-right"></i> 総合認証システム</a>
            </ul>
        </div>

@endsection

@section('script')
@endsection