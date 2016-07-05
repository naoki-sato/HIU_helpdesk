@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            {{-- guest時 --}}
            @if(Auth::guest())
            <div class="panel panel-info">
                <div class="panel-heading">Helpdeskについて</div>

                <div class="panel-body">
                    <h4>ヘルプデスクの案内</h4>
                    <table class="table-condensed">
                        <tbody>
                            <tr>
                                <td>場所</td>
                                <td>松尾記念館 2F</td>
                            </tr>
                            <tr>
                                <td>サポート時間</td>
                                <td>
                                    <div>月〜金曜日(平日) 9:00 - 20:00</div>
                                    <div>土・日・祝日は閉鎖しています。</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>

                    <h4>サポート内容</h4>
                    <ul>
                        <li>実習室および実習室ＰＣ、機器の利用についての相談</li>
                        <li>実習室のトラブル対応</li>
                        <li>パスワードの初期化</li>
                        <li>プリンタおよび大判プリンタの用紙補給</li>
                        <li>機器貸出</li>
                        <li>ソフトウェア提供サービス</li>
                        <div>キャンパスアグリメント申請書受付、インストールＣＤ貸出など</div>
                    </ul>
                    <div>※ヘルプデスクが不在の場合は、情報センター事務室へ来てください。</div> 
                    <hr>

                    <h4>貸出機材について</h4>
                    <div>貸出期間2週間</div>
                    <ul>
                        <li>SONY HD ハンディカム</li>
                        <li>CANON デジタルカメラ</li>
                        <li>カメラ三脚</li>
                        <li>ELECOM USBケーブル</li>
                        <li>SONY iリンクケーブル</li>
                    </ul>
                    <div>貸出期間1日</div>
                    <ul>
                        <li>Logicool ヘッドセット※</li>
                        <li>Logicool レーザポインタ・コントローラ※</li>
                        <li>WACOM INTUOS2 (ペンタブレット)※</li>
                        <div>※これらの機材は学内利用に限らせていただきます。</div>
                    </ul>
                    <div>期限を過ぎても返却がない・紛失した場合は，今後，機材の貸出をお断りする場合がありますので，ご了承ください。</div>
                    <div>貸出の際には，本人の学生証と連絡先(携帯電話の番号など)が必要となります。</div> 
                </div>
            </div>
            @endif


            {{-- ログイン時 --}}
            @if(!Auth::guest())
            <div class="panel panel-info">
                <div class="panel-heading">ログイン(消し忘れ注意:本番では消します)</div>

                <div class="panel-body">
                    <div>
                        E-Mail Address
                        <ul>
                            <li>s1312007@s.do-johodai.ac.jp</li>
                            <li>s1412501@s.do-johodai.ac.jp</li>
                            <li>s1323130@s.do-johodai.ac.jp</li>
                            <li>s1323147@s.do-johodai.ac.jp</li>
                            <li>s1123030@s.do-johodai.ac.jp</li>
                            <li>s1523129@s.do-johodai.ac.jp</li>
                            <li>s1523131@s.do-johodai.ac.jp</li>
                            <li>s1523114@s.do-johodai.ac.jp</li>
                            <li>s1423042@s.do-johodai.ac.jp</li>
                            <li>s1581105@s.do-johodai.ac.jp</li>
                            <li>Helpdesk@s.do-johodai.ac.jp</li>
                        </ul>
                    </div>
                    <div>
                        共通で初期パスワードは 「password」 です。早めに各自パスワード変更してください。
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
