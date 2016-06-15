@extends('layouts.app')

@section('css')
@endsection


@section('content')


    <div><a href="{{url('lost-item')}}">ItemList</a> > edit </div>
    <div>
        <form class="form-horizontal" role="form" method="POST" action="{{ url('lost-item').'/'.$data['id']}}">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="DELETE">

            {{-- 落し物主 --}}
            <div class="form-group{{ $errors->has('student_no') ? ' has-error' : '' }}">
                <label for="lost-item-owner" class="col-xs-2 control-label">落し物主</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="lost-item-owner" placeholder="s学籍番号 or 教職員番号" name="student_no" value="{{$data['student_no'] or ''}}">
                    @if ($errors->has('student_no'))
                        <span class="help-block">
                            <strong>{{ $errors->first('student_no') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('student_name') ? ' has-error' : '' }}">
                <label for="student_name" class="col-xs-2 control-label">氏名</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="student_name" placeholder="情報 太郎" name="student_name" value="{{$data['student_name'] or ''}}">
                    @if ($errors->has('student_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('student_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                <label for="phone" class="col-xs-2 control-label">電話番号</label>
                <div class="col-xs-4">
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="電話番号を聞いてください。" value="{{$data['student_phone_no'] or ''}}">
                    @if ($errors->has('phone'))
                        <span class="help-block">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif
                </div>



                <input type="hidden" name="delivery_staff_id" value="{{Auth::user()->id}}">
            </div>
            <button type="submit" id="submit_delete" class="btn btn-default">SUBMIT</button>
        </form>
    </div>

    <hr>

    {{-- 更新 --}}
    <div>
        <form class="form-horizontal" role="form" method="POST" aaction="{{ url('lost-item').'/'.$data['id']}}">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">
  
            {{-- 受取日 --}}
            <div class="form-group">
                <label for="created_at" class="col-xs-2 control-label">受取日</label>
                <div class="col-xs-9">
                    <input type="date" class="form-control" id="created_at" readonly="readonly" value="{{$data['created_at']}}">
                </div>
            </div>

            {{-- アイテム名 --}}
            <div class="form-group{{ $errors->has('item_name') ? ' has-error' : '' }}">
                <label class="col-xs-2 control-label">アイテム名</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" id="lost_item_name" name="item_name" value="{{$data['lost_item_name']}}" placeholder="ex. 傘, 目薬, etc...">

                    @if ($errors->has('item_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('item_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            
            {{-- 場所 --}}
            <div class="form-group">
                <label class="control-label col-xs-2">場所</label>
                <div class="col-xs-9">
                    @foreach ($places as $p)
                        @if ($p->id == $data['place_id'])
                            <label class="radio-inline" for="{{$p->id}}">
                                <input type="radio" name="place_id" id="{{$p->id}}" value="{{$p->id}}" checked="checked">{{$p->room_name}}　
                            </label>
                        @else
                            <label class="radio-inline" for="{{$p->id}}">
                                <input type="radio" name="place_id" id="{{$p->id}}" value="{{$p->id}}">{{$p->room_name}}　
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- 受取担当者 --}}
            <div class="form-group">
                <label for="reciept_staff_id" class="col-xs-2 control-label">受取担当者</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="reciept_staff_id" value="{{ $data['reciept_staff_name']}}" readonly="readonly">
                </div>
            </div>

            
            {{-- 備考 --}}
            <div class="form-group">
                <label for="note" class="col-xs-2 control-label">備考</label>
                <div class="col-xs-9">
                    <textarea name="note" class="form-control" rows="3" id="note" placeholder="備考">{{$data['note']}}</textarea>
                </div>
            </div>

            {{-- 送信ボタン (アイテム名を記入しないと押せない)--}}
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" id="submit" class="btn btn-default">SUBMIT</button>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/lost-item-show.js')}}"></script>
@endsection