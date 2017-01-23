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
            <li class="active">スタッフ</li>
        </ol>
    </div>
    
    <div class="row">
        {{-- 登録 --}}
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/registration-staff') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('staff_cd') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Staff No</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="staff_cd" value="{{ old('staff_cd') }}" placeholder="学籍(sなし) or 職員番号">

                                @if ($errors->has('staff_cd'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('staff_cd') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('staff_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="staff_name" value="{{ old('staff_name') }}" placeholder="氏名">

                                @if ($errors->has('staff_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('staff_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone_no') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="phone_no" value="{{ old('phone_no') }}" placeholder="電話番号(ハイフンなし)">

                                @if ($errors->has('phone_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Mail Address</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="メールアドレス">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary pull-right">
                                    <i class="glyphicon glyphicon-edit"></i> Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- お知らせ --}}
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Info</div>
                    <div class="panel-body">
                        <div>
                            登録したスタッフの初期役割はStaffなので，Managerにしたい場合は編集リンク先で行ってください。(Staffは管理権限はありません)
                        </div>

                        <table class="table table-condensed">
                            <thead>
                                <tr class="active">
                                    <th>[管理者]</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Admin</td>
                                    <td>◯</td>
                                    <td>◯</td>
                                </tr>
                                <tr>
                                    <td>Manager</td>
                                    <td>◯</td>
                                    <td>◯</td>
                                </tr>
                                <tr>
                                    <td>Staff</td>
                                    <td>◯</td>
                                    <td>◯</td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-condensed">
                            <thead>
                                <tr class="active">
                                    <th>[マネージャ]</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Admin</td>
                                    <td>×</td>
                                    <td>×</td>
                                </tr>
                                <tr>
                                    <td>Manager</td>
                                    <td>◯</td>
                                    <td>◯</td>
                                </tr>
                                <tr>
                                    <td>Staff</td>
                                    <td>◯</td>
                                    <td>◯</td>
                                </tr>
                            </tbody>
                        </table>


                        <div>
                            引退したスタッフを復帰させたい場合は<a href="{{ action('Management\Staff\BeBackStaffController@index') }}">こちら</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- list --}}
        <table id="lost_item_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>氏名</th>
                    <th>学籍番号/教職員番号</th>
                    <th>電話番号</th>
                    <th>役割</th>
                    <th>メールアドレス</th>
                    <th>Show & Edit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $staff)
                    <tr>
                        <th>{{$staff['name']}}</th>
                        <th>{{$staff['staff_cd']}}</th>
                        <th>{{$staff['phone_no']}}</th>
                        <th>{{$staff['role']}}</th>
                        <th>{{$staff['email']}}</th>
                        <th><a href="{{url('registration-staff') . '/' . $staff['id']}}" class="btn btn-default btn-block btn-sm"><i class="glyphicon glyphicon-link"></i>link</a></th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

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
            deferRender: true,
        });
    });
    </script>
@endsection