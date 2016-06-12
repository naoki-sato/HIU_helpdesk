@extends('layouts.app')

@section('css')
@endsection


@section('content')


    <div><a href="{{url('lost-item')}}">ItemList</a> > edit </div>
    <div>
        <form class="form-horizontal" role="form">

            {{-- 落し物主 --}}
            <div class="form-group">
                <label for="lost-item-owner" class="col-xs-2 control-label">落し物主</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="lost-item-owner" name="student_no" value="{{$data['student_no'] or ''}}" readonly="readonly">
                </div>
            </div>

            <div class="form-group">
                <label for="student_name" class="col-xs-2 control-label">氏名</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="student_name" name="student_name" value="{{$data['student_name'] or ''}}" readonly="readonly">
                </div>
            </div>


            <div class="form-group">
                <label for="phone" class="col-xs-2 control-label">電話番号</label>
                <div class="col-xs-4">
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{$data['student_phone_no'] or ''}}" readonly="readonly">
                </div>
                <div class="btn btn-default disabled">引渡済み</div>
            </div>
        </form>
    </div>

    <hr>

    {{-- 更新 --}}
    <div>
        <form class="form-horizontal" role="form">
  
            {{-- 受取日 --}}
            <div class="form-group">
                <label for="created_at" class="col-xs-2 control-label">受取日</label>
                <div class="col-xs-9">
                    <input type="date" class="form-control" id="created_at" readonly="readonly" value="{{$data['created_at']}}">
                </div>
            </div>

            {{-- 引渡日 --}}
            <div class="form-group">
                <label for="deleted_at" class="col-xs-2 control-label">引渡日</label>
                <div class="col-xs-9">
                    <input type="date" class="form-control" id="deleted_at" readonly="readonly" value="{{$data['deleted_at']}}">
                </div>
            </div>


            {{-- アイテム名 --}}
            <div class="form-group">
                <label class="col-xs-2 control-label">アイテム名</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control" id="lost_item_name" name="item_name" value="{{$data['lost_item_name']}}" readonly="readonly">
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

            {{-- 引渡担当者 --}}
            <div class="form-group">
                <label for="delivery_staff" class="col-xs-2 control-label">引渡担当者</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="delivery_staff" value="{{$data['delivery_staff_name']}}" readonly="readonly">
                </div>
            </div>
            
            {{-- 備考 --}}
            <div class="form-group">
                <label for="note" class="col-xs-2 control-label">備考</label>
                <div class="col-xs-9">
                    <textarea name="note" class="form-control" rows="3" id="note" placeholder="備考" readonly="readonly">{{$data['note']}}</textarea>
                </div>
            </div>

            {{-- 送信ボタン (アイテム名を記入しないと押せない)--}}
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="btn btn-default disabled">引渡済み</div>
                </div>
            </div>
        </form>
    </div>

@endsection