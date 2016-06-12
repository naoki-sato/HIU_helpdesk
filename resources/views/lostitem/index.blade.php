@extends('layouts.app')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap.min.css') }}" />
@endsection


@section('content')

    {{-- タブメニュー --}}
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">Registration</a></li>
        <li><a href="#tab2" data-toggle="tab">ItemList</a></li>
        <li><a href="#tab3" data-toggle="tab">Export</a></li>
    </ul>

    {{-- タブコンテンツ --}}
    <div class="tab-content">
        {{-- 登録タブ --}}
        <div class="tab-pane fade in active" id="tab1">
            <div class="col-md-8 col-md-offset-2">
                <form class="form-horizontal" role="form" method="POST">
                {{ csrf_field() }}
      
                    {{-- 受取日 --}}
                    <div class="form-group">
                        <label for="created_at" class="col-xs-2 control-label">受取日</label>
                        <div class="col-xs-4">
                            <input type="date" class="form-control" id="created_at" readonly="readonly" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    {{-- アイテム名 --}}
                    <div class="form-group{{ $errors->has('item_name') ? ' has-error' : '' }}">
                        <label class="col-xs-2 control-label">アイテム名</label>
                        <div class="col-xs-9">
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
                        <label class="control-label col-xs-2">場所</label>
                        <div class="col-xs-7">
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
                        <label for="reciept_staff_id" class="col-xs-2 control-label">受取担当者</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="reciept_staff_id" value="{{Auth::user()->staff_no}}" name="staff_no" readonly="readonly">
                            <input type="hidden" value="{{Auth::user()->id}}" name="staff_id">
                        </div>
                    </div>
                    
                    {{-- 備考 --}}
                    <div class="form-group">
                        <label for="note" class="col-xs-2 control-label">備考</label>
                        <div class="col-xs-9">
                            <textarea name="note" class="form-control" rows="3" id="note" placeholder="備考"></textarea>
                        </div>
                    </div>

                    {{-- 送信ボタン (アイテム名を記入しないと押せない)--}}
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" id="submit" class="btn btn-default">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- 落し物リストタブ --}}
        <div class="tab-pane fade" id="tab2">
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
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>

        {{-- 過去の落し物タブ --}}
        <div class="tab-pane fade" id="tab3">

            <div class="well well-lg">
                <ul>
                    <li>基本的にはヘルプデスクリーダがやればいいと思います。</li>
                    <li>.xls形式でエクスポートします。</li>
                    <li>通し番号は半角数字のみ</li>
                    <li></li>
                </ul>
            </div>

            {{-- 通し番号 / 履歴 radio_btn --}}
            <div class="form-horizontal col-xs-12">
                <div class="form-group">
                    <label class="control-label col-xs-2">選択</label>
                    <div class="col-xs-7">
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
                <div class="col-xs-12 contents_top_margin">
                    <form class="form-horizontal" action="/lost-item-export/serial" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('start_number') ? ' has-error' : '' }}{{ $errors->has('end_number') ? ' has-error' : '' }}">
                            <label class="control-label col-xs-2">通し番号</label>
                            <div class="col-xs-7"> 
                                <div class="form-inline">

                                    <input type="number" class="form-control positive-integer antirc" id="start_number" name="start_number" placeholder="ex.1" min="1" value="{{ old('start_number') }}" required> 〜                          
                                    <input type="number" class="form-control positive-integer antirc" id="end_number" name="end_number" placeholder="ex.100" min="1" value="{{ old('end_number') }}" required>
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

                                
                                <button type="submit" id="serial_submit" class="btn btn-default btn_top_margin">DOWNLOAD</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>{{-- serial_number_contents --}}

            <div id="history_contents">
                <div class="col-xs-12 contents_top_margin">
                    <form class="form-horizontal" action="/lost-item-export/history" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-xs-2">年度</label>
                                <div class="col-xs-5">
                                    <select class="form-control" id="year_select" name="year">
                                        @for ($i = 2016; $i <= fiscalYear(); $i++)
                                            <option value="{{$i}}" selected>{{$i}}</option>
                                        @endfor
                                    </select>
                                    
                                    <button type="submit" id="submit" class="btn btn-default btn_top_margin">DOWNLOAD</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>{{-- history_contents --}}
        </div>
    </div>
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

        $('#lost_item_list').dataTable({
            processing: true,
            pageLength: 25, 
            orderClasses: false,
            searching:true,
            lengthChange:false,
            order: [[0,'desc']], // ID
            columns: [
                { data: "id", defaultContent: "", "title": "ID"},
                { data: "created_at", defaultContent: "", "title": "受取日"},
                { data: "lost_item_name", defaultContent: "", "title": "アイテム" },
                { data: "room_name", defaultContent: "", "title": "場所"},
                { data: "reciept_staff_name", defaultContent: "", "title": "受取担当" },
                { data: "note", defaultContent: "", "title": "備考"},
                { data: "deleted_at", defaultContent: "", "title": "引渡日"},
                { data: "delivery_staff_name", defaultContent: "", "title": "引渡担当"},
                { data: "student_name", defaultContent: "", "title": "落し物主"},
                { data: "id", "render": function (data, type, row, meta) {
        return '<a href="{{url('lost-item').'/'}}' + data + '" class="btn btn-default btn-block">' + 'link' + '</a>';
                    }, "title": "show & edit"},
            ],
            deferRender: true,
            ajax: {
               url: "{{url('/lost-item-api?year='). app('request')->input('year')}}", 
               dataSrc: "", {{-- 消してはダメ(わざと空白) --}}
               type: "GET"
            }
        });
    });
    </script>
    <script type="text/javascript" src="{{URL::asset('/js/lost-item.js')}}"></script>
@endsection