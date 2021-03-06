@extends('layouts.app')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap.min.css') }}" />
@endsection


@section('content')

    {{-- パンくずリスト --}}
    <div>
        <ol class="breadcrumb">
            <li><a href="{{url('/')}}">Top</a></li>
            <li class="active">貸出 / 返却</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">貸出</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('lend-item/rental')}}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('user_cd') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">学籍/職員番号</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="user_cd" value="{{ old('user_cd') }}" id="user_cd" placeholder="学籍(sなし) or 職員番号" value="{{$data['user_cd'] or ''}}" required>

                                @if ($errors->has('user_cd'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_cd') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">氏名</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="user_name" value="{{ old('user_name') }}" id="user_name" placeholder="情報 太郎" value="{{$data['user_name'] or ''}}" required>

                                @if ($errors->has('user_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone_no') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">電話番号</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="phone_no" value="{{ old('phone_no') }}" placeholder="電話番号(ハイフンなし)" id="phone_no" value="{{$data['phone_no'] or ''}}" required>

                                @if ($errors->has('phone_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        {{-- 貸出アイテム --}}
                        <div class="form-group{{ $errors->has('rental_item') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">貸出アイテム</label>
                            <div class="col-md-6">
                                @for ($i = 0; $i < 5; $i++)
                                    <input type="text" class="form-control" name="rental_item[]"  placeholder="貸出アイテム" id="rental_item">
                                @endfor
                            </div>
                        </div>


                        {{-- 備考 --}}
                        <div class="form-group">
                            <label for="note" class="col-md-4 control-label">備考</label>
                            <div class="col-md-6">
                                <textarea name="note" class="form-control" rows="3" id="note" placeholder="備考"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="button" onclick="submit();" id="submit_lend" class="btn btn-primary pull-right">
                                    <i class="glyphicon glyphicon-edit"></i> Register
                                </button>
                            </div>
                        </div>
                    </form>  
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Info</div>
                <div class="panel-body">
                    <div>
                        機材の貸し出し期間は 2 週間です。<br>
                        返却日は <span class="h4">{{\Carbon\Carbon::now()->addDays(14)->format('Y/m/d')}}</span> になります。<br>
                        ただし、ヘッドセット・ペンタブの返却は本日中です。
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">返却</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('lend-item/return')}}">
                        {{ csrf_field() }}

                        {{-- 返却アイテム --}}
                        <div class="form-group{{ $errors->has('return_item') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">返却アイテム</label>
                            <div class="col-md-6">
                                @for ($i = 0; $i < 5; $i++)
                                    <input type="text" class="form-control" name="return_item[]"  placeholder="返却アイテム" id="return_item">
                                @endfor
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="button" onclick="submit();" id="submit_lend" class="btn btn-primary pull-right">
                                    <i class="glyphicon glyphicon-edit"></i> Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Export

                </div>
                <div class="panel-body">
                    <div class="col-md-10">
                        <form class="form-horizontal pull-right" action="/lend-item-export" method="POST">
                            {{ csrf_field() }}
                            返却済みの処理も含む。　
                            <button type="submit" class="btn btn-primary pull-right btn-sm">
                                <i class="glyphicon glyphicon-download-alt"></i> Export
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- 貸出リスト --}}
    <table id="lend_item_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>受付</th>
                <th>機材コード</th>
                <th>説明</th>
                <th>学籍番号</th>
                <th>氏名</th>
                <th>電話番号</th>
                <th>貸出担当</th>
                <th>貸出日付</th>
                <th>備考</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->item_cd}}</td>
                    <td>{{$item->description}}</td>
                    <td>{{$item->user_cd}}</td>
                    <td>{{$item->user_name}}</td>
                    <td>{{$item->phone_no}}</td>
                    <td>{{$item->lended_staff_name}}</td>
                    <td>{{$item->created_at}}</td>
                    <td>{{$item->comment}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
    $(function(){
        $('#lend_item_list').dataTable({
            processing: true,
            pageLength: 25,
            orderClasses: false,
            searching:true,
            lengthChange:false,
            order: [[0,'desc']], // ID
            deferRender: true,
        });
    });
    </script>

    <script type="text/javascript" src="{{URL::asset('/js/lend-item.js')}}"></script>
@endsection