@extends('layouts.app')

@section('css')
@endsection


@section('content')


    <div><a href="{{url('lost-item')}}">ItemList</a> > edit </div>
    <div class="">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('lost-item').'/'.$data['id']}}">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="DELETE">
            <div class="form-group{{ $errors->has('student_no') ? ' has-error' : '' }}">
                <label for="lost-item-owner" class="col-sm-2 control-label">落し物主</label>
                <div class="col-xs-4">
                    <input type="text" class="form-control" id="lost-item-owner" placeholder="s学籍番号 or 教職員番号" name="student_no" value="{{$data['student']['student_no'] or ''}}">
                    @if ($errors->has('student_no'))
                        <span class="help-block">
                            <strong>{{ $errors->first('student_no') }}</strong>
                        </span>
                    @endif
                </div>
                <input type="hidden" name="delivery_staff_id" value="{{Auth::user()->id}}">
                @if(empty($data['deleted_at']))
                    <button type="submit" id="submit_delete" class="btn btn-default">SUBMIT</button>
                @else
                    <div class="btn btn-default disabled">引渡済み</div>
                @endif
            </div>
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
                <label for="created_at" class="col-sm-2 control-label">受取日</label>
                <div class="col-xs-4">
                    <input type="date" class="form-control" id="created_at" readonly="readonly" value="{{$data['created_at']}}">
                </div>
            </div>
            {{-- 引渡日 --}}
            @if(isset($data['deleted_at']))
                <div class="form-group">
                    <label for="deleted_at" class="col-sm-2 control-label">引渡日</label>
                    <div class="col-xs-4">
                        <input type="date" class="form-control" id="deleted_at" readonly="readonly" value="{{$data['deleted_at']}}" readonly>
                    </div>
                </div>

            @endif

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
                    <input type="text" class="form-control" id="reciept_staff_id" value="{{ $data['reciept_staff']['name']}}" readonly="readonly">
                </div>
            </div>

            {{-- 引渡担当者 --}}
            @if(isset($data['delivery_staff']['name']))
                <div class="form-group">
                    <label for="delivery_staff" class="col-xs-2 control-label">引渡担当者</label>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" id="delivery_staff" value="{{$data['delivery_staff']['name']}}" readonly="readonly">
                    </div>
                </div>
            @endif
            
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
                    @if(empty($data['deleted_at']))
                        <button type="submit" id="submit" class="btn btn-default">SUBMIT</button>
                    @else
                        <div class="btn btn-default disabled">引渡済み</div>
                    @endif
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/lost-item-show.js')}}"></script>
@endsection