@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    <div>
                        php artisan db:seed してから下記でログインしてください。
                    </div>
                    <div>
                        E-Mail Address
                        <ul>
                            <li>s1581105@s.do-johodai.ac.jp</li>
                            <li>s1312092@s.do-johodai.ac.jp</li>
                            <li>s1312007@s.do-johodai.ac.jp</li>
                            <li>Helpdesk@s.do-johodai.ac.jp</li>
                            <li>hoge_staff@s.do-johodai.ac.jp</li>
                        </ul>
                    </div>
                    <div>
                        共通でパスワードは password です。
                    </div>
                    <div>
                        詳しくは detabase > seeds > UserTableSeeder.php をみてください。
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
