@extends('layouts.app')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('css/lity.min.css') }}" />
@endsection


@section('content')

    {{-- パンくずリスト --}}
    <div>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}">Top</a></li>
            <li class="active">落し物</li>
        </ol>
    </div>


    <div class="row">
        {{-- 登録 --}}
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">


                    <form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST">
                    {{ csrf_field() }}
      
                        {{-- 受取日 --}}
                        <div class="form-group">
                            <label for="created_at" class="col-md-3 control-label">受取日</label>
                            <div class="col-md-8">
                                <input type="date" class="form-control" id="created_at" readonly="readonly" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        {{-- アイテム名 --}}
                        <div class="form-group{{ $errors->has('item_name') ? ' has-error' : '' }}">
                            <label class="col-md-3 control-label">アイテム名</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="lost_item_name" name="item_name" value="{{ old('item_name') }}" placeholder="ex. 傘, 目薬, etc...">

                                @if ($errors->has('item_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('item_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- 場所 --}}
                        <div class="form-group">
                            <label class="control-label col-md-3">場所</label>
                            <div class="col-md-8">
                                @foreach ($places as $p)
                                    @if ($p->id==1)
                                        <label class="radio-inline" for="{{$p->id}}">
                                            <input type="radio" name="place_id" id="{{$p->id}}" value="{{$p->id}}" checked="checked">{{$p->room_name}}
                                        </label>
                                    @else
                                        <label class="radio-inline" for="{{$p->id}}">
                                            <input type="radio" name="place_id" id="{{$p->id}}" value="{{$p->id}}">{{$p->room_name}}
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- 受取担当者 --}}
                        <div class="form-group">
                            <label for="reciept_staff_id" class="col-md-3 control-label">受取担当者</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="reciept_staff_id" value="{{Auth::user()->staff_cd}}" name="staff_cd" readonly="readonly">
                                <input type="hidden" value="{{Auth::user()->id}}" name="staff_id">
                            </div>
                        </div>
                        
                        {{-- 備考 --}}
                        <div class="form-group">
                            <label for="note" class="col-md-3 control-label">備考</label>
                            <div class="col-md-8">
                                <textarea name="note" class="form-control" rows="3" id="note" placeholder="備考"></textarea>
                            </div>
                        </div>

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

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Export</div>
                <div class="panel-body">
                    <div class="well well-lg">
                        <ul>
                            <li>.xls形式でエクスポートします。</li>
                            <li>通し番号は半角数字のみ</li>
                        </ul>
                    </div>

                    {{-- 通し番号 / 履歴 radio_btn --}}
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-3">選択</label>
                            <div class="col-md-8">
                                <label class="radio-inline" for="serial_number">
                                    <input type="radio" name="export" id="serial_number" value="1" checked>通し番号
                                </label>
                                <label class="radio-inline" for="history">
                                    <input type="radio" name="export" id="history" value="2">履歴
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- exportコンテンツ内容 --}}
                    <div id="serial_number_contents">
                        <div class="col-md-12">
                            <form class="form-horizontal" action="/lost-item-export/serial" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('start_number') ? ' has-error' : '' }}{{ $errors->has('end_number') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3">通し番号</label>
                                    <div class="col-md-9">
                                        <div class="col-md-6">
                                            <input type="number" class="form-control positive-integer antirc" id="start_number" name="start_number" placeholder="min" min="1" value="{{ old('start_number') }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <input type="number" class="form-control positive-integer antirc" id="end_number" name="end_number" placeholder="max" min="1" value="{{ old('end_number') }}" required>
                                        </div>

                                        @if ($errors->has('start_number'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('start_number') }}</strong>
                                            </span>
                                        @endif

                                        @if ($errors->has('end_number'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('end_number') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-5">
                                        <button type="submit" id="serial_submit" class="btn btn-primary pull-right">
                                            <i class="glyphicon glyphicon-download-alt"></i> Export
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>{{-- serial_number_contents --}}

                    <div id="history_contents">
                        <div class="col-md-12">
                            <form class="form-horizontal" action="/lost-item-export/history" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="control-label col-md-3">年度</label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="year_select" name="year">
                                            @for ($i = 2016; $i <= fiscalYear(); $i++)
                                                <option value="{{$i}}" selected>{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-5">
                                        <button type="submit" id="submit" class="btn btn-primary pull-right">
                                            <i class="glyphicon glyphicon-download-alt"></i> Export
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>{{-- history_contents --}}
                </div>
            </div>
        </div>
    </div>{{-- row --}}
        
    {{-- 検索フォーム --}}
    <div class="row">

        <div class="col-sm-12 year_select">
            <form method="GET" class="form-inline pull-right">
                <div class="form-group form-group-sm">
                    <select class="form-control" id="year_select" name="year">
                        @for ($i = 2016; $i <= fiscalYear(); $i++)
                            <option value="{{$i}}" selected>{{$i}}</option>
                        @endfor
                    </select>
                </div>
                <input type="submit" value="SUBMIT" class="btn btn-default btn-sm">
            </form>
        </div>
    </div>
    {{-- list --}}
    <table id="lost_item_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>画像</th>
                <th>受取日</th>
                <th>アイテム</th>
                <th>場所</th>
                <th>受取担当</th>
                <th>備考</th>
                <th>引渡日</th>
                <th>引渡担当</th>
                <th>落とし主</th>
                <th>show&edit</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{$item['id']}}</td>
                <td><a href="{{url('image') . '/' . $item['file_name']}}" data-lity="data-lity"><img src="{{url('image') . '/'. $item['file_name']}}" class="thumbnail" height=50></a></td>
                <td>{{$item['created_at']}}</td>
                <td>{{$item['lost_item_name']}}</td>
                <td>{{$item['room_name']}}</td>
                <td>{{$item['reciept_staff_name']}}</td>
                <td>{{$item['note']}}</td>
                <td>{{$item['deleted_at']}}</td>
                <td>{{$item['delivery_staff_name']}}</td>
                <td>{{$item['user_name']}}</td>
                <td><a href="{{url('lost-item') . '/' . $item['id']}}" class="btn btn-default btn-block"><i class="glyphicon glyphicon-link"></i>link</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/js/lity.min.js')}}"></script>
    <script>
    $(function(){
        $('#file_input').change(function() {
            $('#dummy_file').val($(this).val());
        });

        $('#lost_item_list').dataTable({
            processing: true,
            pageLength: 25, 
            orderClasses: false,
            searching:true,
            lengthChange:false,
            order: [[0,'desc']], // ID
            deferRender: true,
        });
    });
    </script>
    <script type="text/javascript" src="{{URL::asset('/js/lost-item.js')}}"></script>
@endsection