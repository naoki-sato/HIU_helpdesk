@extends('layouts.app')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
    {{-- パンくずリスト --}}
    <div>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}">Top</a></li>
            <li class="active">各種ダウンロード</li>
        </ol>
    </div>



    <div class="row">
        <div class="col-md-12">


            <div class="panel panel-default">
                <div class="panel-heading">FILES</div>
                <div class="panel-body">


                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr class="active">
                            <th>ファイル名</th>
                            @if(in_array(Auth::user()->role, ['admin', 'manager']))
                                <th>削除</th>
                            @endif
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td><a href="{{action('Download\DownloadController@show', ['file' => $file])}}">{{ $file }}</a></td>
                                @if(in_array(Auth::user()->role, ['admin', 'manager']))
                                    <th>
                                        <form method="post" action="{{action('Download\DownloadController@destroy', ['file' => $file])}}">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="submit" id="delete" value="delete" class="btn btn-danger btn-link btn-sm btn-destroy">
                                        </form>
                                    </th>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>





    @if(in_array(Auth::user()->role, ['admin', 'manager']))
        <div class="row">
            {{-- 登録 --}}
            <div class="col-md-6 pull-right">
                <div class="panel-body">
                    <form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST">
                        {{ csrf_field() }}

                        {{-- 画像 --}}
                        <div class="form-group{{ $errors->has('file_input') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3">UPLOAD</label>
                            <div class="col-md-8">
                                <div class="input-group col-md-12">
                                    <input type="file" id="file_input" name="file_input" style="display: none;">
                                    <div class="input-group col-md-12">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" onclick="$('#file_input').click();"><i class="glyphicon glyphicon-folder-open"></i></button>
                                        </span>
                                        <input id="dummy_file" type="text" class="form-control" placeholder="select file..." disabled>
                                    </div>

                                    @if ($errors->has('file_input'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('file_input') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>


                        {{-- 送信ボタン (アイテム名を記入しないと押せない)--}}
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-5">
                                <button type="submit" id="submit" class="btn btn-primary pull-right">
                                    <i class="glyphicon glyphicon-edit"></i> SUBMIT
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif




@endsection


@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/management-staff.js')}}"></script>
    <script>
        $(function(){
            $('#file_input').change(function() {
                $('#dummy_file').val($(this).val());
            });
        });
    </script>
@endsection