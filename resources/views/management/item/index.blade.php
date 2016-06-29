@extends('layouts.app')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap.min.css') }}" />
@endsection

@section('content')

    {{-- パンくずリスト --}}
    <div>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}">Top</a></li>
            <li class="active">貸出アイテム</li>
        </ol>
    </div>

    
    <div class="row">
        {{-- 登録 --}}
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <div class="col-md-10 col-md-offset-1">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/registration-item') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('item_cd') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Item Code</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="item_cd" value="{{ old('item_cd') }}" placeholder="ex. CAMERA_TRIPOD_01" required>

                                    @if ($errors->has('item_cd'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('item_cd') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('serial_cd') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Serial Code</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="serial_cd" value="{{ old('serial_cd') }}" placeholder="ex. audio-technica ATV-475" required>

                                    @if ($errors->has('serial_cd'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('serial_cd') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Description</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="description" value="{{ old('description') }}" placeholder="ex. カメラ用三脚" required>

                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
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
                <div class="panel-heading">Import / Export</div>
                <div class="panel-body">
                    {{-- Inport / Export radio_btn --}}
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-3">選択</label>
                            <div class="col-md-8">
                                <label class="radio-inline" for="input">
                                    <input type="radio" name="select" id="input" value="1" checked>Import
                                </label>
                                <label class="radio-inline" for="output">
                                    <input type="radio" name="select" id="output" value="2">Export
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- importコンテンツ内容 --}}
                    <div id="import">
                        <div class="col-md-12">
                            <div>以下の形式の CSV ファイルをアップロードしてください。
                                <ul>
                                    <li>header(1行目)に item_cd,serial_cd,description 追加</li>
                                    <li>必要な項目：機材コード、シリアルナンバー、機材説明</li>
                                    <li>区切り文字：「 ,（コンマ）」</li>
                                    <li>文字コード：UTF-8</li>
                                </ul>
                            </div> 
                            <div class="col-md-11 pull-right">
                                <form class="form-inline" enctype="multipart/form-data" action="/registration-item-excel/import" name="form" method="post">
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
                            </div>
                        </div>
                    </div>{{-- inport --}}

                    <div id="export">
                        <div class="col-md-11 col-md-offset-1">
                        <form class="form-horizontal" action="/registration-item-excel/export" method="POST">
                            {{ csrf_field() }}
                            登録されているアイテムを.csvで出力　
                            <button type="submit" class="btn btn-primary">
                                <i class="glyphicon glyphicon-download-alt"></i> Export
                            </button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
    {{-- 貸出アイテム一覧 --}}
    <table id="item_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead></thead>
        <tbody></tbody>
    </table>
@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#item_list').dataTable({
            processing: true,
            pageLength: 25, 
            orderClasses: false,
            searching:true,
            lengthChange:false,
            order: [[0,'desc']], // ID
            columns: [
                { data: "id", defaultContent: "", "title": "ID"},
                { data: "item_cd", defaultContent: "", "title": "Item Code" },
                { data: "serial_cd", defaultContent: "", "title": "Serial Code"},
                { data: "description", defaultContent: "", "title": "Description" },
            ],
            deferRender: true,
            ajax: {
               url: "{{url('/registration-item-api')}}", 
               dataSrc: "", {{-- 消してはダメ(わざと空白) --}}
               type: "GET"
            }
        });


    });
    </script>
    <script type="text/javascript" src="{{URL::asset('/js/registration-item.js')}}"></script>


@endsection