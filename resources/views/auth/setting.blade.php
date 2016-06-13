@extends('layouts.app')

@section('css')
@endsection


@section('content')
    <div><a href="{{url('/')}}">Top</a> > Setting </div>

    {{-- 更新 --}}
    <div>
        <form class="form-horizontal" role="form" method="POST" action="{{ url('setting')}}">
        {{ csrf_field() }}

            {{-- 学籍番号・職員番号 --}}
            <div class="form-group">
                <label for="staff_no" class="col-md-4 control-label">学籍番号/職員番号</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="staff_no" value="{{ $data['staff_no']}}" readonly="readonly">
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
                    <input type="text" class="form-control" id="phone_no" value="{{ $data['phone_no']}}" name="phone_no" placeholder="電話番号">
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

            {{-- 送信ボタン --}}
            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" id="submit" class="btn btn-default">UPDATE</button>
                </div>
            </div>
        </form>

    </div>

@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/lost-item-show.js')}}"></script>
@endsection