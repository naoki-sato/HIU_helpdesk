@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap.min.css') }}" />
@endsection


@section('content')

    {{-- list --}}
    <h4>{{fiscalYear()}}年度の落し物一覧</h4>
    <table id="lost_item_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>登録日時</th>
                <th>アイテム</th>
                <th>場所</th>
                <th>備考</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $value)
                <tr>
                    <td>{{$value->id}}</td>
                    <td>{{$value->created_at}}</td>
                    <td>{{$value->lost_item_name}}</td>
                    <td>{{$value->room_name}}</td>
                    <td>{{$value->note}}</td>
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
                { data: "note", defaultContent: "", "title": "備考"},

            ],
            deferRender: true,
        });
    });
    </script>
@endsection