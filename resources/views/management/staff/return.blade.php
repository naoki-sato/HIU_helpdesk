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
        <thead>
            <tr>
                <th>名前</th>
                <th>学籍番号/教職員番号</th>
                <th>電話番号</th>
                <th>役割</th>
                <th>メールアドレス</th>
                <th>Can Be Back</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $staff)
                <tr>
                    <td>{{$staff['name']}}</td>
                    <td>{{$staff['staff_cd']}}</td>
                    <td>{{$staff['phone_no']}}</td>
                    <td>{{$staff['role']}}</td>
                    <td>{{$staff['email']}}</td>
                    <td>
                        <form action="{{url('can-be-back-staff').'/'.$staff['id']}}" method="post">
                            <input name="_method" type="hidden" value="PUT">
                            {!! csrf_field() !!}
                            <input name="id" type="hidden" value="{{$staff['id']}}">
                            <button type="submit" id="submit" class="btn btn-default btn-block btn-sm">
                                <i class="glyphicon glyphicon-heart-empty"></i> Be Back
                            </button>
                        </form>
                    </td>
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
        $('#return_staff_list').dataTable({
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