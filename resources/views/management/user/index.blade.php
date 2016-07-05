@extends('layouts.app')

@section('css')    
    <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap.min.css') }}" />
@endsection

@section('content')

    {{-- パンくずリスト --}}
    <div>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}">Top</a></li>
            <li class="active">ユーザ</li>
        </ol>
    </div>

    
    <div class="row">
        {{-- 登録 --}}
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <div class="col-md-11">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/registration-user/register') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('user_cd') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">学籍/職員番号</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="user_cd" value="{{ old('user_cd') }}" placeholder="ex. s1234567" required>

                                    @if ($errors->has('user_cd'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('user_cd') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">氏名</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="user_name" value="{{ old('user_name') }}" placeholder="ex. 情報 太郎" required>

                                    @if ($errors->has('user_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('user_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-6">
                                    <button type="submit" class="btn btn-primary pull-right">
                                        <i class="glyphicon glyphicon-edit"></i> Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Import</div>
                <div class="panel-body">

                    {{-- importコンテンツ内容 --}}
                    <div id="import">
                        <div class="col-md-12">
                            <div>以下の形式の CSV ファイルをアップロードしてください。
                                <ul>
                                    <li>header(1行目)に user_cd,user_name 追加</li>
                                    <li>必要な項目：学籍(sなし)/職員番号、氏名</li>
                                    <li>区切り文字：「 ,（コンマ）」</li>
                                    <li>文字コード：UTF-8</li>
                                </ul>
                            </div> 
                            <div class="col-md-11 pull-right">
                                <form class="form-inline" enctype="multipart/form-data" action="/registration-user/import" name="form" method="post">
                                {{ csrf_field() }}
                                    <div class="form-group {{ $errors->has('file_input') ? ' has-error' : '' }}">
                                        <div class="input-group col-md-12">
                                            <input type="file" id="file_input" name="file_input" style="display: none;">
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" onclick="$('#file_input').click();"><i class="glyphicon glyphicon-folder-open"></i></button>
                                                </span>
                                                <input id="dummy_file" type="text" class="form-control" placeholder="select file..." disabled>       
                                            </div>
                                        </div>

                                    </div>
                                    <button type="submit" name="csv_up" class="btn btn-primary"><i class="glyphicon glyphicon-cloud-upload"></i> Import</button>
                                    @if ($errors->has('file_input'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('file_input') }}</strong>
                                        </span>
                                    @endif
                                </form>

                                <form class="form-inline" action="/registration-user/example" name="form" method="post">
                                {{ csrf_field() }}
                                    <button type="submit" class="btn btn-link btn-sm"><i class="glyphicon glyphicon-download-alt"></i> example</button>
                                </form>
                            </div>
                        </div>
                    </div>{{-- inport --}}


                </div>
            </div>
        </div>
    </div>


    
    {{-- 貸出アイテム一覧 --}}
    <table id="user_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>学籍/職員番号</th>
                <th>氏名</th>
                <th>電話番号</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $value)
                <tr>
                    <td>{{$value->user_cd}}</td>
                    <td>{{$value->user_name}}</td>
                    <td>{{$value->phone_no}}</td>
                </tr>  
            @endforeach
        </tbody>
    </table>
@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
    $(function(){
        $('#user_list').dataTable({
            processing: true,
            pageLength: 25, 
            orderClasses: false,
            searching:true,
            lengthChange:false,
            deferRender: true,
        });
        $('#file_input').change(function() {
            $('#dummy_file').val($(this).val());
        });
    });

    </script>
@endsection