@extends('layouts.app')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap.min.css') }}" />
@endsection


@section('content')

    {{-- タブメニュー --}}
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">Registration / Return</a></li>
        <li><a href="#tab2" data-toggle="tab">LendedList</a></li>
        <li><a href="#tab3" data-toggle="tab">Export</a></li>
    </ul>

    {{-- タブコンテンツ --}}
    <div class="tab-content">
        {{-- 登録タブ --}}
        <div class="tab-pane fade in active" id="tab1">

            <div class="col-md-6">

                <div class="panel panel-default">
                    <div class="panel-heading">貸出</div>
                    <div class="panel-body">
                        <div>
                            機材の貸し出し期間は 2 週間です。<br>
                            返却日は 2016年06月28日 になります。<br>
                            ただし、ヘッドセット・ペンタブは本日中に返却してもらってください。
                        </div>
                        <form class="form-horizontal" role="form" method="POST">
                        {{ csrf_field() }}
                  
                            <div class="form-group{{ $errors->has('student_no') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">学籍/職員番号</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="student_no" value="{{ old('student_no') }}" id="student_no" placeholder="s学籍番号 or 職員番号" value="{{$data['student_no'] or ''}}" required>

                                    @if ($errors->has('student_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('student_no') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('student_name') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">氏名</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="student_name" value="{{ old('student_name') }}" id="student_name" placeholder="情報 太郎" value="{{$data['student_name'] or ''}}" required>

                                    @if ($errors->has('student_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('student_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">電話番号</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="電話番号" id="phone" value="{{$data['phone'] or ''}}" required>

                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            {{-- 貸出アイテム --}}
                            <div class="form-group{{ $errors->has('lend_item') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">貸出アイテム</label>
                                <div class="col-md-6">
                                    @for ($i = 0; $i < 5; $i++)
                                        <input type="text" class="form-control" name="lend_item[]"  placeholder="貸出アイテム" id="lend_item">
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
                                    <button type="submit" id="submit_lend" class="btn btn-primary">
                                        <i class="fa fa-btn fa-user"></i>Register
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>

            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">返却</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST">
                            {{ csrf_field() }}
                            <input name="_method" type="hidden" value="PUT">

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
                                    <button type="submit" id="submit_lend" class="btn btn-primary">
                                        <i class="fa fa-btn fa-user"></i>Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- 落し物リストタブ --}}
        <div class="tab-pane fade" id="tab2">
            {{-- list --}}
            <table id="lost_item_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>

        {{-- 過去の落し物タブ --}}
        <div class="tab-pane fade" id="tab3">

            


            
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{URL::asset('/js/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#lost_item_list').dataTable({
            processing: true,
            pageLength: 25, 
            orderClasses: false,
            searching:true,
            lengthChange:false,
            order: [[0,'desc']], // ID
            columns: [
                { data: "id", defaultContent: "", "title": "受付"},
                { data: "item_code", defaultContent: "", "title": "機材コード"},
                { data: "description", defaultContent: "", "title": "説明"},
                { data: "student_no", defaultContent: "", "title": "学籍番号" },
                { data: "student_name", defaultContent: "", "title": "氏名"},
                { data: "phone_no", defaultContent: "", "title": "電話番号"},
                { data: "lended_staff_name", defaultContent: "", "title": "貸出担当"},
                { data: "created_at", defaultContent: "", "title": "貸出日時"},
                { data: "comment", defaultContent: "", "title": "備考"},
                { data: "id", "render": function (data, type, row, meta) {
        return '<a href="{{url('lost-item').'/'}}' + data + '" class="btn btn-default btn-block">' + 'link' + '</a>';
                    }, "title": "show & edit"},
            ],
            deferRender: true,
            ajax: {
               url: "{{url('/lend-item-api'). app('request')->input('year')}}", 
               dataSrc: "", {{-- 消してはダメ(わざと空白) --}}
               type: "GET"
            }
        });
    });
    </script>

    <script type="text/javascript" src="{{URL::asset('/js/lend-item.js')}}"></script>
@endsection