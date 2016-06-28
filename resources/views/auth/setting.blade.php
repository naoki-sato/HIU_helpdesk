@extends('layouts.app')

@section('css')
@endsection


@section('content')
    {{-- パンくずリスト --}}
    <div>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}">Top</a></li>
            <li class="active">Setting</li>
        </ol>
    </div>
    {{-- 更新 --}}
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Change Phone and Mail</div>
            <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('setting/edit')}}">
                {{ csrf_field() }}

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
                    <div class="form-group{{ $errors->has('phone_no') ? ' has-error' : '' }}">
                        <label for="phone_no" class="col-md-4 control-label">電話番号</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="phone_no" value="{{ $data['phone_no']}}" name="phone_no" placeholder="電話番号(ハイフンなし)">
                            @if ($errors->has('phone_no'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone_no') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- メールアドレス --}}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="mail" class="col-md-4 control-label">メールアドレス</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="email" value="{{ $data['email']}}" name="email" placeholder="メールアドレス">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" id="submit" class="btn btn-primary pull-right">
                                <i class="glyphicon glyphicon-edit"></i> Change
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Reset Password</div>
            <div class="panel-body">

                <form class="form-horizontal" role="form" action="{{ url('setting/reset-password') }}" method="post">
                {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('pw') ? ' has-error' : '' }}">
                        <label for="pw" class="col-md-4 control-label">現在のパスワード</label>
                        <div class="col-md-6">
                            <input type="password" name="pw" class="form-control" placeholder="現在のパスワード"/>
                            @if ($errors->has('pw'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('pw') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">New Password</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" placeholder="新しいパスワード">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="新しいパスワード(再確認)">

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
                                <i class="glyphicon glyphicon-refresh"></i> Reset
                            </button>
                        </div>
                    </div>







                </form>

            </div>
        </div>
    </div>








@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/lost-item-show.js')}}"></script>
@endsection