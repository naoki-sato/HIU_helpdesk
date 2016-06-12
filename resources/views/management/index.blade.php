@extends('layouts.app')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap.min.css') }}" />
@endsection

@section('content')

    {{-- タブメニュー --}}
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">Registration</a></li>
        <li><a href="#tab2" data-toggle="tab">StaffList</a></li>
    </ul>

    {{-- タブコンテンツ --}}
    <div class="tab-content">

        {{-- スタッフ登録タブ --}}
        <div class="tab-pane fade in active" id="tab1">

            <div class="col-md-8 col-md-offset-2">
                 <form class="form-horizontal" role="form" method="POST" action="{{ url('/registration-staff') }}">
                     {!! csrf_field() !!}


                     <div class="form-group{{ $errors->has('staff_no') ? ' has-error' : '' }}">
                         <label class="col-md-4 control-label">Staff No</label>

                         <div class="col-md-6">
                             <input type="text" class="form-control" name="staff_no" value="{{ old('staff_no') }}" placeholder="s学籍番号 or 教職員番号">

                             @if ($errors->has('staff_no'))
                                 <span class="help-block">
                                     <strong>{{ $errors->first('staff_no') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>

                     

                     <div class="form-group{{ $errors->has('staff_name') ? ' has-error' : '' }}">
                         <label class="col-md-4 control-label">Name</label>

                         <div class="col-md-6">
                             <input type="text" class="form-control" name="staff_name" value="{{ old('staff_name') }}" placeholder="氏名">

                             @if ($errors->has('staff_name'))
                                 <span class="help-block">
                                     <strong>{{ $errors->first('staff_name') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>

                     <div class="form-group{{ $errors->has('phone_no') ? ' has-error' : '' }}">
                         <label class="col-md-4 control-label">Phone</label>

                         <div class="col-md-6">
                             <input type="text" class="form-control" name="phone_no" value="{{ old('phone_no') }}" placeholder="電話番号">

                             @if ($errors->has('phone_no'))
                                 <span class="help-block">
                                     <strong>{{ $errors->first('phone_no') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>

                     <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                         <label class="col-md-4 control-label">Mail Address</label>

                         <div class="col-md-6">
                             <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="メールアドレス">

                             @if ($errors->has('email'))
                                 <span class="help-block">
                                     <strong>{{ $errors->first('email') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>

                     {{-- <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                         <label class="control-label col-md-4">Role</label>
                         <div class="col-md-6">

                             <label class="radio-inline" for="manager">
                                 <input type="radio" name="role" id="manager" value="manager">Manager
                             </label>

                             <label class="radio-inline" for="staff">
                                 <input type="radio" name="role" id="staff" value="staff" checked="checked">Staff
                             </label>
                             @if ($errors->has('role'))
                                 <span class="help-block">
                                     <strong>{{ $errors->first('role') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div> --}}
                         

                     <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                         <label class="col-md-4 control-label">Password</label>

                         <div class="col-md-6">
                             <input type="password" class="form-control" name="password">

                             @if ($errors->has('password'))
                                 <span class="help-block">
                                     <strong>{{ $errors->first('password') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>

                     <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                         <label class="col-md-4 control-label">Confirm Password</label>

                         <div class="col-md-6">
                             <input type="password" class="form-control" name="password_confirmation">

                             @if ($errors->has('password_confirmation'))
                                 <span class="help-block">
                                     <strong>{{ $errors->first('password_confirmation') }}</strong>
                                 </span>
                             @endif
                         </div>
                     </div>

                     <div class="form-group">
                         <div class="col-md-6 col-md-offset-4">
                             <button type="submit" class="btn btn-primary">
                                 <i class="fa fa-btn fa-user"></i>Register
                             </button>
                         </div>
                     </div>
                 </form>
            </div>

        </div>

        {{-- スタッフ一覧 --}}
        <div class="tab-pane fade" id="tab2">
            {{-- list --}}
            <table id="lost_item_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead></thead>
                <tbody></tbody>
            </table>
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
                { data: "name", defaultContent: "", "title": "名前"},
                { data: "staff_no", defaultContent: "", "title": "学籍番号/教職員番号" },
                { data: "phone_no", defaultContent: "", "title": "電話番号"},
                { data: "role", defaultContent: "", "title": "役割" },
                { data: "email", defaultContent: "", "title": "メアド"},
                { data: "id", "render": function (data, type, row, meta) {
        return '<a href="{{url('registration-staff').'/'}}' + data + '" class="btn btn-default btn-block">' + 'link' + '</a>';
                    }, "title": "show & edit"},
            ],
            deferRender: true,
            ajax: {
               url: "{{url('/registration-staff-api')}}", 
               dataSrc: "", {{-- 消してはダメ(わざと空白) --}}
               type: "GET"
            }
        });


    });
    </script>

@endsection