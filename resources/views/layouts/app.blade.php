<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Helpdesk</title>

    {{-- Fonts --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('css/common.css') }}" />

    @yield('css')
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Helpdesk
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                @if (Auth::guest())
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/lost-property') }}"><i class="glyphicon glyphicon-question-sign"></i> 落し物一覧</a></li>
                    </ul>
                @endif
                @if (!Auth::guest())
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/lend-item') }}"><i class="glyphicon glyphicon-transfer"></i> 貸出 / 返却</a></li>
                        <li><a href="{{ url('/lost-item') }}"><i class="glyphicon glyphicon-question-sign"></i> 落し物</a></li>
                    </ul>

                    @if(in_array(Auth::user()->role, ['admin', 'manager']))
                    <ul class="nav navbar-nav">         
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="glyphicon glyphicon-list"></i> 管理関連<span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                {{-- 管理人かマネージャーのみ表示 (スタッフは除く) --}}
                                <li>
                                    <a href="{{ url('/registration-item') }}"><i class="glyphicon glyphicon-share-alt"></i> 貸出アイテム</a></li>
                                <li>
                                    <a href="{{ url('/registration-staff') }}">
                                    <i class="glyphicon glyphicon-user"></i> スタッフ</a>
                                </li>
                                {{-- 管理人のみ表示 (マネージャ，スタッフは除く) --}}
                                @if(in_array(Auth::user()->role, ['admin']))
                                    <li>
                                        <a href="{{ url('/registration-user') }}">
                                        <i class="glyphicon glyphicon-education"></i> ユーザ</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                    @endif

                @endif

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>

                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="glyphicon glyphicon-user"></i>
                                {{ Auth::user()->staff_cd }} / {{ Auth::user()->name }}<span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ URL::to('/setting') }}"><i class="glyphicon glyphicon-cog"></i> Setting</a></li>
                                <li><a href="{{ URL::to('/logout') }}"><i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    {{-- main --}}
    <div id="wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                {{-- フラッシュメッセージの表示 --}}
                {{-- お知らせメッセージ --}}
                @if(Session::has('info_message'))
                    <div class="alert alert-info">{!! Session::get('info_message') !!}</div>
                @endif
                {{-- 成功メッセージ --}}
                @if(Session::has('success_message'))
                    <div class="alert alert-success">{!! Session::get('success_message') !!}</div>
                @endif
                {{-- 警告メッセージ --}}
                @if(Session::has('alert_message'))
                    <div class="alert alert-danger">{!! Session::get('alert_message') !!}</div>
                @endif

                {{-- content --}}
                @yield('content')
                </div>
            </div>
        </div>
    </div>

    {{-- footer --}}
    <footer class="footer">
        <div class="container">
            <div class="col-md-12">

                @if (!Auth::guest())
                <table class="table-condensed">
                    <thead>
                        <tr>
                            <th><i class="glyphicon glyphicon-link"></i> Links</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="https://groups.google.com/a/s.do-johodai.ac.jp/forum/#!forum/helpdesk" target="_blank"><i class="glyphicon glyphicon-triangle-right"></i> Helpdesk Google Group</a></td>
                        </tr>
                        <tr>
                            <td><a href="{{url('http://helon.do-johodai.ac.jp/wiki/')}}" target="_blank"><i class="glyphicon glyphicon-triangle-right"></i> Helpdesk Wikipedia</a></td>
                        </tr>
                        <tr>
                            <td><a href="http://account.do-johodai.ac.jp" target="_blank"><i class="glyphicon glyphicon-triangle-right"></i> 総合認証システム</a></td>
                        </tr>
                    </tbody>
                </table>
                @endif

                <p class="text-muted pull-right">&copy; 2016 - {{ date("Y") }} nakajima.lab</p>
            </div>
        </div>
    </footer>

    {{-- JavaScripts --}}
    <script type="text/javascript" src="{{URL::asset('/js/jquery-2.1.1.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/js/bootstrap.min.js')}}"></script>

    @yield('script')
</body>
</html>
