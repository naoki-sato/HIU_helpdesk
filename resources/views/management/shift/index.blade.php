@extends('layouts.app')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
    {{-- パンくずリスト --}}
    <div>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}">Top</a></li>
            <li class="active">シフト表</li>
        </ol>
    </div>
    
    <div>
        {{-- */$img_path = "image/" . $image_name/* --}}
        <img class="thumbnail" src="{{ URL::to($img_path)}}" width="100%">
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
                        <label class="control-label col-md-3">画像</label>
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
    <script>
        $(function(){
            $('#file_input').change(function() {
                $('#dummy_file').val($(this).val());
            });
        });
    </script>
@endsection