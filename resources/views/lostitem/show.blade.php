@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('css/lity.min.css') }}" />
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
                <form class="form-horizontal" role="form" method="POST" action="{{ url('lost-item').'/'.$data['id']}}">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="DELETE">

                    {{-- 落し物主 --}}
                    <div class="form-group{{ $errors->has('user_cd') ? ' has-error' : '' }}">
                        <label for="lost-item-owner" class="col-md-3 control-label">落し物主</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="lost-item-owner" placeholder="学籍(sなし) or 教職員番号" name="user_cd" value="{{$data['user_cd'] or old('user_cd')}}">
                            @if ($errors->has('user_cd'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user_cd') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
                        <label for="user_name" class="col-md-3 control-label">氏名</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="user_name" placeholder="情報 太郎" name="user_name" value="{{$data['user_name'] or old('user_name')}}">
                            @if ($errors->has('user_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group{{ $errors->has('phone_no') ? ' has-error' : '' }}">
                        <label for="phone_no" class="col-md-3 control-label">電話番号</label>
                        <div class="col-md-8">
                            <input type="tel" class="form-control" id="phone_no" name="phone_no" placeholder="電話番号(ハイフンなし)" value="{{$data['phone_no'] or old('phone_no')}}">
                            @if ($errors->has('phone_no'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone_no') }}</strong>
                                </span>
                            @endif
                        </div>

                        <input type="hidden" name="delivery_staff_id" value="{{Auth::user()->id}}">
                    </div>


                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-5">
                            <button type="submit" id="submit_delete" class="btn btn-primary pull-right">
                                <i class="glyphicon glyphicon-ok"></i> SUBMIT
                                </button>
                        </div>
                    </div>
                </form>

                <hr>

                {{-- 更新 --}}
                <form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST" action="{{ url('lost-item').'/'.$data['id']}}">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
          
                    {{-- 受取日 --}}
                    <div class="form-group">
                        <label for="created_at" class="col-md-3 control-label">受取日</label>
                        <div class="col-md-8">
                            <input type="date" class="form-control" id="created_at" readonly="readonly" value="{{$data['created_at']}}">
                        </div>
                    </div>

                    {{-- アイテム名 --}}
                    <div class="form-group{{ $errors->has('item_name') ? ' has-error' : '' }}">
                        <label class="col-md-3 control-label">アイテム名</label>
                        <div class="col-md-8">
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

                    
                    {{-- 備考 --}}
                    <div class="form-group">
                        <label for="note" class="col-md-3 control-label">備考</label>
                        <div class="col-md-8">
                            <textarea name="note" class="form-control" rows="3" id="note" placeholder="備考">{{$data['note']}}</textarea>
                        </div>
                    </div>



                    {{-- 画像 --}}
                    <div class="form-group{{ $errors->has('file_input') ? ' has-error' : '' }}">
                        <label for="note" class="col-md-3 control-label">画像</label>
                        <div class="col-md-2">
                            @if($data['file_name'] != 'no_image')
                                {{-- */$img_path = 'images_store/lost-item' .'/'. $data['file_name']/* --}}
                            @else
                                {{-- */$img_path = 'images/noimage.jpg'/* --}}
                            @endif

                            <a href="{{ asset($img_path) }}" data-lity="data-lity">
                                <img class="thumbnail" src="{{ asset($img_path) }}" height=75>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <input type="file" id="file_input" name="file_input" style="display: none;">
                            <div class="input-group col-md-12">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="$('#file_input').click();"><i class="glyphicon glyphicon-folder-open"></i></button>
                                </span>
                                <input id="dummy_file" type="text" class="form-control" placeholder="select file..." disabled>
                            </div>
                            @if ($errors->has('file_input'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('file_input') }}</strong>
                                </span>
                            @endif  
                            <div>*注意 : 上書きされます。</div>
                        </div>
                    </div>

                    {{-- 送信ボタン (アイテム名を記入しないと押せない)--}}
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-5">
                            <button type="submit" id="submit" class="btn btn-primary pull-right">
                                <i class="glyphicon glyphicon-ok"></i> SUBMIT
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
    <script type="text/javascript" src="{{URL::asset('/js/lity.min.js')}}"></script>
@endsection