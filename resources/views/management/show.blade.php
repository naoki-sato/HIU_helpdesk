@extends('layouts.app')

@section('css')
@endsection


@section('content')
    <div><a href="{{url('registration-staff')}}">RegistrationStaff</a> > edit </div>

    {{-- 更新 --}}
    <div>
        <form class="form-horizontal" role="form" method="POST" action="{{ url('registration-staff').'/'.$data['id']}}">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">


            {{-- 学籍番号・教員番号 --}}
            <div class="form-group">
                <label for="staff_no" class="col-xs-2 control-label">氏名</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="staff_no" value="{{ $data['staff_no']}}" readonly="readonly">
                </div>
            </div>

            {{-- 氏名 --}}
            <div class="form-group">
                <label for="staff_name" class="col-xs-2 control-label">氏名</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="staff_name" value="{{ $data['name']}}" readonly="readonly">
                </div>
            </div>

            {{-- 電話番号 --}}
            <div class="form-group">
                <label for="phone_no" class="col-xs-2 control-label">電話番号</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="phone_no" value="{{ $data['phone_no']}}">
                </div>
            </div>

            {{-- メールアドレス --}}
            <div class="form-group">
                <label for="mail" class="col-xs-2 control-label">メールアドレス</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="mail" value="{{ $data['email']}}">
                </div>
            </div>

            {{-- 役割 --}}
            <div class="form-group">
                <label class="control-label col-xs-2">役割</label>
                <div class="col-xs-9">


                    @if(Auth::user()->role === 'admin')
                        <label class="radio-inline" for="admin">
                            <input type="radio" name="role" id="admin" value="admin" checked="checked">Admin　
                        </label>
                    @endif

                    <label class="radio-inline" for="manager">
                        @if($data['role'] == 'manager')
                            <input type="radio" name="role" id="manager" value="manager" checked="checked">Manager
                        @else
                            <input type="radio" name="role" id="manager" value="manager" >Manager
                        @endif
                    </label>

                    <label class="radio-inline" for="staff">
                        @if($data['role'] == 'staff')
                            <input type="radio" name="role" id="staff" value="staff" checked="checked">Staff
                        @else
                            <input type="radio" name="role" id="staff" value="staff" >Staff
                        @endif
                    </label>


                </div>
            </div>

            {{-- 送信ボタン (アイテム名を記入しないと押せない)--}}
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    @if(empty($data['deleted_at']))
                        <button type="submit" id="submit" class="btn btn-default">SUBMIT</button>
                    @else
                        <div class="btn btn-default disabled">引渡済み</div>
                    @endif
                </div>
            </div>
        </form>
    </div>


    <div>
        <form class="form-horizontal" role="form" method="POST" action="{{ url('registration-staff').'/'.$data['id']}}">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="DELETE">
            <input name="staff_id" type="hidden" value="{{$data['id']}}">

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    @if(empty($data['deleted_at']))
                        <button type="submit" id="submit" class="btn btn-default">DELETE</button>
                    @else
                        <div class="btn btn-default disabled">引渡済み</div>
                    @endif
                </div>
            </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/lost-item-show.js')}}"></script>
@endsection