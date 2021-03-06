@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('css/lity.min.css') }}" />
@endsection


@section('content')

    {{-- list --}}
    <h4>{{fiscalYear()}}年度の落し物一覧</h4>
    <table id="lost_item_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>画像</th>
                <th>登録日時</th>
                <th>アイテム</th>
                <th>場所</th>
                <th>備考</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $value)
                @if (!isset($value->deleted_at))
                <tr>
                    <td>{{$value->id}}</td>
                    <td>
                        <a href="image-1.jpg" data-lity="data-lity">
                            @if($value->file_name != 'no_image')
                                {{-- */$img_path = "images_store/lost-item/" . $value->file_name/* --}}
                            @else
                                {{-- */$img_path = 'images/noimage.jpg'/* --}}
                            @endif
                            <a href="{{ asset($img_path) }}" data-lity="data-lity">
                                <img class="thumbnail" src="{{asset($img_path)}}" height=75>
                            </a>
                        </a>
                    </td>
                    <td>{{$value->created_at}}</td>
                    <td>{{$value->lost_item_name}}</td>
                    <td>{{$value->room_name}}</td>
                    <td>{{$value->note}}</td>
                </tr>
                @endif
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
@endsection