@extends('layouts.app')

@section('css')
@endsection


@section('content')

    {{-- パンくずリスト --}}
    <div>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}">Top</a></li>
            <li><a href="{{url('/registration-staff')}}">スタッフ</a></li>
            <li class="active">編集</li>
        </ol>
    </div>


    {{-- 更新 --}}
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Setting</div>
            <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('registration-staff').'/'.$data['id']}}">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                <input name="id" type="hidden" value="{{$data['id']}}">


                    {{-- 学籍番号・職員番号 --}}
                    <div class="form-group">
                        <label for="staff_no" class="col-md-4 control-label">学籍番号/職員番号</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="staff_cd" value="{{ $data['staff_cd']}}" readonly="readonly">
                        </div>
                    </div>

                    {{-- 氏名 --}}
                    <div class="form-group">
                        <label for="staff_name" class="col-md-4 control-label">氏名</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="staff_name" value="{{ $data['name']}}" readonly="readonly">
                        </div>
                    </div>

                    {{-- 電話番号 --}}
                    <div class="form-group">
                        <label for="phone_no" class="col-md-4 control-label">電話番号</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="phone_no" value="{{ $data['phone_no']}}" readonly="readonly">
                        </div>
                    </div>

                    {{-- メールアドレス --}}
                    <div class="form-group">
                        <label for="mail" class="col-md-4 control-label">メールアドレス</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="email" value="{{ $data['email']}}" readonly="readonly">
                        </div>
                    </div>

                    {{-- 役割 --}}
                    <div class="form-group">
                        <label class="control-label col-md-4">役割</label>
                        <div class="col-md-6">
                        
                        {{-- 以下の複雑なコード、いいやり方あれば直して；； --}}
                            @if(Auth::user()->role === 'admin')
                                @if($data['role'] === 'admin')
                                    <label class="radio-inline" for="admin">
                                        <input type="radio" name="role" id="admin" value="admin" checked="checked">Admin　
                                    </label>
                                    <label class="radio-inline" for="manager">
                                        <input type="radio" name="role" id="manager" value="manager">Manager
                                    </label>
                                    <label class="radio-inline" for="staff">
                                        <input type="radio" name="role" id="staff" value="staff">Staff
                                    </label>
                                @elseif($data['role'] === 'manager')
                                    <label class="radio-inline" for="admin">
                                        <input type="radio" name="role" id="admin" value="admin">Admin　
                                    </label>
                                    <label class="radio-inline" for="manager">
                                        <input type="radio" name="role" id="manager" value="manager" checked="checked">Manager
                                    </label>
                                    <label class="radio-inline" for="staff">
                                        <input type="radio" name="role" id="staff" value="staff">Staff
                                    </label>
                                @else
                                    <label class="radio-inline" for="admin">
                                        <input type="radio" name="role" id="admin" value="admin">Admin　
                                    </label>
                                    <label class="radio-inline" for="manager">
                                        <input type="radio" name="role" id="manager" value="manager">Manager
                                    </label>
                                    <label class="radio-inline" for="staff">
                                        <input type="radio" name="role" id="staff" value="staff" checked="checked">Staff
                                    </label>
                                @endif
                            @else {{-- manager --}}
                                @if($data['role'] === 'admin')
                                    <label class="radio-inline" for="admin">
                                        <input type="radio" name="role" id="admin" value="admin" checked="checked" disabled>Admin　
                                    </label>
                                    <label class="radio-inline" for="manager">
                                        <input type="radio" name="role" id="manager" value="manager" disabled>Manager
                                    </label>
                                    <label class="radio-inline" for="staff">
                                        <input type="radio" name="role" id="staff" value="staff" disabled>Staff
                                    </label>
                                @elseif($data['role'] === 'manager')
                                    <label class="radio-inline" for="admin">
                                        <input type="radio" name="role" id="admin" value="admin" disabled>Admin　
                                    </label>
                                    <label class="radio-inline" for="manager">
                                        <input type="radio" name="role" id="manager" value="manager" checked="checked">Manager
                                    </label>
                                    <label class="radio-inline" for="staff">
                                        <input type="radio" name="role" id="staff" value="staff">Staff
                                    </label>
                                @else
                                    <label class="radio-inline" for="admin">
                                        <input type="radio" name="role" id="admin" value="admin" disabled>Admin　
                                    </label>
                                    <label class="radio-inline" for="manager">
                                        <input type="radio" name="role" id="manager" value="manager">Manager
                                    </label>
                                    <label class="radio-inline" for="staff">
                                        <input type="radio" name="role" id="staff" value="staff" checked="checked">Staff
                                    </label>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- 送信ボタン --}}
                    @if((Auth::user()->role === 'admin') || (Auth::user()->role === 'manager') && ($data['role'] !== 'admin'))
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" id="submit" class="btn btn-primary pull-right">
                                    <i class="glyphicon glyphicon-edit"></i> UPDATE
                                </button>
                            </div>
                        </div>
                    @endif
                </form>

                @if((Auth::user()->role === 'admin') || (Auth::user()->role === 'manager') && ($data['role'] !== 'admin'))
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('registration-staff').'/'.$data['id']}}">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="DELETE">
                        <input name="staff_id" type="hidden" value="{{$data['id']}}">

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" id="delete" class="btn btn-link btn-xs pull-right">
                                    <i class="glyphicon glyphicon-remove"></i> DELETE
                                    </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/management-staff.js')}}"></script>
@endsection