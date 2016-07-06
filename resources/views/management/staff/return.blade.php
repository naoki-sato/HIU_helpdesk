@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap.min.css') }}" />
@endsection

@section('content')

    {{-- パンくずリスト --}}
    <div>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}">Top</a></li>
            <li><a href="{{url('/registration-staff')}}">スタッフ</a></li>
            <li class="active">過去スタッフ</li>
        </ol>
    </div>
    

    {{-- list --}}
    <table id="return_staff_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead></thead>
        <tbody></tbody>
    </table>

@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
    $(function(){
        $('#return_staff_list').dataTable({
            processing: true,
            pageLength: 25, 
            orderClasses: false,
            searching:true,
            lengthChange:false,
            order: [[0,'desc']], // ID
            columns: [
                { data: "name", defaultContent: "", "title": "名前"},
                { data: "staff_cd", defaultContent: "", "title": "学籍番号/教職員番号" },
                { data: "phone_no", defaultContent: "", "title": "電話番号"},
                { data: "role", defaultContent: "", "title": "役割" },
                { data: "email", defaultContent: "", "title": "メールアドレス"},
                { data: "id", "render": function (data, type, row, meta) {
                return '<form action="{{url('return-staff').'/'}}'+ data +'" method="post"><input name="_method" type="hidden" value="PUT">{!! csrf_field() !!}<input name="id" type="hidden" value="'+data+'"><button type="submit" id="submit" class="btn btn-default btn-block btn-sm"><i class="glyphicon glyphicon-heart-empty"></i>'+ ' Return' + '</button></form>';
                    }, "title": "Work Return"},
            ],
            deferRender: true,
            ajax: {
               url: "{{url('/return-staff-api')}}", 
               dataSrc: "", {{-- 消してはダメ(わざと空白) --}}
               type: "GET"
            }
        });


    });
    </script>

@endsection