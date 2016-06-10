@extends('layouts.app')

@section('css')
@endsection


@section('content')

    {{-- タブメニュー --}}
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">Registration</a></li>
        <li><a href="#tab2" data-toggle="tab">B</a></li>
        <li><a href="#tab3" data-toggle="tab">C</a></li>
    </ul>

    {{-- タブコンテンツ --}}
    <div class="tab-content">


        {{-- 落し物リストタブ --}}
        <div class="tab-pane fade in active" id="tab1">

                <div class="col-md-8 col-md-offset-2">

                        
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/registration-staff') }}">
                                {!! csrf_field() !!}

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

                                <div class="form-group{{ $errors->has('staff_no') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Staff No</label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="staff_no" value="{{ old('staff_no') }}" placeholder="s学籍番号 or 教職員番号">

                                        @if ($errors->has('staff_no'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('staff_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('phone_no') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Phone</label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="phone_no" value="{{ old('phone_no') }}" placeholder="電話番号">

                                        @if ($errors->has('phone_no'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('phone_no') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4">Role</label>
                                    <div class="col-md-6">

                                        <label class="radio-inline" for="manager">
                                            <input type="radio" name="role" id="manager" value="manager">Manager
                                        </label>

                                        <label class="radio-inline" for="staff">
                                            <input type="radio" name="role" id="staff" value="staff" checked="checked">Staff
                                        </label>
                                        @if ($errors->has('role'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('role') }}</strong>
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
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-user"></i>Register
                                        </button>
                                    </div>
                                </div>
                            </form>
                        


                </div>

        </div>

        {{-- 登録タブ --}}
        <div class="tab-pane fade" id="tab2">
            b
        </div>

        {{-- 過去の落し物タブ --}}
        <div class="tab-pane fade" id="tab3">
            c
        </div>
    </div>
@endsection

@section('script')

@endsection