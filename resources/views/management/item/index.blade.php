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
    </ul>

    {{-- タブコンテンツ --}}
    <div class="tab-content">

        {{-- 貸出アイテム登録タブ --}}
        <div class="tab-pane fade in active" id="tab1">

            <div class="col-md-8 col-md-offset-2">
                 <form class="form-horizontal" role="form" method="POST" action="{{ url('/registration-item') }}">
                     {!! csrf_field() !!}

                     <div class="form-group{{ $errors->has('item_code') ? ' has-error' : '' }}">
                         <label class="col-md-4 control-label">Item Code</label>

                         <div class="col-md-6">
                             <input type="text" class="form-control" name="item_code" value="{{ old('item_code') }}" placeholder="ex. CAMERA_TRIPOD_01" required>

                             @if ($errors->has('item_code'))
                                 <span class="help-block">
                                     <strong>{{ $errors->first('item_code') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>

                     <div class="form-group{{ $errors->has('serial_code') ? ' has-error' : '' }}">
                         <label class="col-md-4 control-label">Serial Code</label>

                         <div class="col-md-6">
                             <input type="text" class="form-control" name="serial_code" value="{{ old('serial_code') }}" placeholder="ex. audio-technica ATV-475" required>

                             @if ($errors->has('serial_code'))
                                 <span class="help-block">
                                     <strong>{{ $errors->first('serial_code') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>

                     <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                         <label class="col-md-4 control-label">Description</label>

                         <div class="col-md-6">
                             <input type="text" class="form-control" name="description" value="{{ old('description') }}" placeholder="ex. カメラ用三脚" required>

                             @if ($errors->has('description'))
                                 <span class="help-block">
                                     <strong>{{ $errors->first('description') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>

                     <div class="form-group">
                         <div class="col-md-6 col-md-offset-4">
                             <button type="submit" class="btn btn-primary">
                                 <i class="fa fa-btn fa-user"></i>Register
                             </button>
                         </div>
                     </div>
                 </form>
            </div>

        </div>

        {{-- 貸出アイテム一覧 --}}
        <div class="tab-pane fade" id="tab2">
            {{-- list --}}
            <table id="item_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead></thead>
                <tbody></tbody>
            </table>
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

        $('#item_list').dataTable({
            processing: true,
            pageLength: 25, 
            orderClasses: false,
            searching:true,
            lengthChange:false,
            order: [[0,'desc']], // ID
            columns: [
                { data: "id", defaultContent: "", "title": "ID"},
                { data: "item_code", defaultContent: "", "title": "Item Code" },
                { data: "serial_code", defaultContent: "", "title": "Serial Code"},
                { data: "description", defaultContent: "", "title": "Description" },
                { data: "id", "render": function (data, type, row, meta) {
        return '<a href="{{url('registration-item').'/'}}' + data + '" class="btn btn-default btn-block">' + 'link' + '</a>';
                    }, "title": "show & edit"},
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

@endsection