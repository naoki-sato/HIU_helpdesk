@extends('layouts.app')

@section('css')
@endsection


@section('content')
    <div><a href="{{url('registration-item')}}">RegistrationItem</a> > edit </div>
    {{-- 更新 --}}
    <div>
        <form class="form-horizontal" role="form" method="POST" action="{{ url('registration-item').'/'.$data['id']}}">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">
        <input name="id" type="hidden" value="{{$data['id']}}">


            {{-- Item Code --}}
            <div class="form-group">
                <label for="item_code" class="col-md-4 control-label">Item Code</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="item_code" value="{{ $data['item_code']}}" name="item_code" placeholder="ex. CAMERA_TRIPOD_01" required>
                </div>
            </div>

            {{-- Serial Code --}}
            <div class="form-group">
                <label for="serial_code" class="col-md-4 control-label">Serial Code</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="serial_code" value="{{ $data['serial_code']}}" name="serial_code" placeholder="ex. audio-technica ATV-475" required>
                </div>
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label for="description" class="col-md-4 control-label">Description</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="description" value="{{ $data['description']}}" name="description" placeholder="ex. カメラ用三脚" required>
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
    {{-- <script type="text/javascript" src="{{URL::asset('/js/lost-item-show.js')}}"></script> --}}
@endsection