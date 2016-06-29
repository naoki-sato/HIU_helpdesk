@extends('layouts.app')

@section('css')
@endsection


@section('content')
    {{-- パンくずリスト --}}
    <div>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}">Top</a></li>
            <li><a href="{{url('/lost-item')}}">落し物</a></li>
            <li class="active">詳細</li>
        </ol>
    </div>
    
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Detail</div>
            <div class="panel-body">
                <div class="form-horizontal" role="form">

                    {{-- 落し物主 --}}
                    <div class="form-group">
                        <label for="lost-item-owner" class="col-md-3 control-label">落し物主</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="lost-item-owner" name="user_no" value="{{$data['user_no'] or ''}}" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="user_name" class="col-md-3 control-label">氏名</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="user_name" name="user_name" value="{{$data['user_name'] or ''}}" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="col-md-3 control-label">電話番号</label>
                        <div class="col-md-8">
                            <input type="tel" class="form-control" id="phone" name="phone" value="{{$data['student_phone_no'] or ''}}" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-5">
                            <button type="submit" id="delete" class="btn btn-default disabled pull-right">
                                <i class="glyphicon glyphicon-ok"></i> Completed
                                </button>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-horizontal" role="form">
            
                    {{-- 受取日 --}}
                    <div class="form-group">
                        <label for="created_at" class="col-md-3 control-label">受取日</label>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="created_at" readonly="readonly" value="{{$data['created_at']}}">
                        </div>
                    </div>

                    {{-- 引渡日 --}}
                    <div class="form-group">
                        <label for="deleted_at" class="col-md-3 control-label">引渡日</label>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="deleted_at" readonly="readonly" value="{{$data['deleted_at']}}">
                        </div>
                    </div>


                    {{-- アイテム名 --}}
                    <div class="form-group">
                        <label class="col-md-3 control-label">アイテム名</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="lost_item_name" name="item_name" value="{{$data['lost_item_name']}}" readonly="readonly">
                        </div>
                    </div>


                    
                    {{-- 場所 --}}
                    <div class="form-group">
                        <label class="control-label col-md-3">場所</label>
                        <div class="col-md-8">
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
                        <label for="reciept_staff_id" class="col-md-3 control-label">受取担当者</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="reciept_staff_id" value="{{ $data['reciept_staff_name']}}" readonly="readonly">
                        </div>
                    </div>

                    {{-- 引渡担当者 --}}
                    <div class="form-group">
                        <label for="delivery_staff" class="col-md-3 control-label">引渡担当者</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="delivery_staff" value="{{$data['delivery_staff_name']}}" readonly="readonly">
                        </div>
                    </div>
                    
                    {{-- 備考 --}}
                    <div class="form-group">
                        <label for="note" class="col-md-3 control-label">備考</label>
                        <div class="col-md-8">
                            <textarea name="note" class="form-control" rows="3" id="note" placeholder="備考" readonly="readonly">{{$data['note']}}</textarea>
                        </div>
                    </div>

                    {{-- 画像 --}}
                    <div class="form-group">
                        <label for="note" class="col-md-3 control-label">画像</label>
                        <div class="col-md-2">
                            @if($data['file_name'])
                                {{-- */$img_path = "image/" . $data['file_name']/* --}}
                                <img class="thumbnail" src="{{ URL::to($img_path) }}" height=75>
                            @else
                                {{-- */$img_path = "image/noimage.jpg"/* --}}
                                <img class="thumbnail" src="{{ URL::to($img_path) }}" height=75>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-5">
                            <button type="submit" id="delete" class="btn btn-default disabled pull-right">
                                <i class="glyphicon glyphicon-ok"></i> Completed
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection