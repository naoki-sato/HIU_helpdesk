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

                {{-- 伝言 --}}
                @if(count($info_mes))
                    <div class="alert alert-info">
                        <ul>
                            @foreach ($info_mes as $val)
                                <li>{{ $val->description . ' (' . $val->created_at. ') by ' . $val->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
        
                <div class="panel panel-info">
                    <div class="panel-heading">伝言(30日で消えます)</div>

                    <div class="panel-body">
                        <form action="{{ url('/') }}" method="post">
                        {!! csrf_field() !!}

                            <div class="input-group{{ $errors->has('info_mes') ? ' has-error' : '' }}">

                                <input type="text" class="form-control" name="info_mes" value="{{ old('info_mes') }}" placeholder="伝言" required>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">SUBMIT</button>
                                </span>
                            </div>
                            <div class="col-md-offset-4 col-md-8">
                                @if ($errors->has('info_mes'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('info_mes') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">基本業務</div>

                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                09:00
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    本部9:00~シフトの方は10分前には必ず出勤。<br>
                                    情報センターに行っておはよう。入って右側にある鍵をもらって本部オプーン。<br>
                                    (9:00から講義でマイクを使うので、本部から外にマイクトレイを必ず出しておく。遅いと激おこだからね)
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                シフト交代・引き継ぎ
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    何かあったら次の人にも全部伝えることね。<br>
                                    情報共有(シフトまたいでインストール作業する学生いたら、申請書控え渡しを次の人に引き継いでもらう等も)<br>
                                    PCは交代時、ログオフで渡してあげて。
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                18:00 実習室1,3,LLメディア,105 施錠
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    情報センターから鍵を借りてLL実習室と105教室を施錠します。<br>
                                    両実習室の施錠時に作業中の学生がいた場合、施錠の旨を伝えた上で、実習室2と画像メディア実習室が20時まで利用出来る事を伝えましょう。<br>
                                    [ 16:00~18:00の方 ]がやってあげたほうがいいかな。
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                20:00
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    本部18:00~シフトの方は、実習室2、画像メディアを閉める。<br>
                                    本部の施錠作業は以下。<br>
                                    HelpDesk PCの電源落とす。（サーバの方は落としちゃダメ・ゼッタイ）<br>
                                    マイクトレイを本部の中に入れる。<br>
                                    マイク電池の充電をする。マイクには充電が終わってる電池を入れる<br>
                                    (忘れると、電池切れで講義が中断されるのでマズイ！)<br>
                                    鍵を返す。<br>
                                    情報センターが閉まっているときは、扉の下から入れとく。<br>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                各実習室施錠作業
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    忘れ物がないかチェックしながら、イス、キーボードやマウス、ヘッドセットの位置をなおす。<br>
                                    PCのシャットダウンをしてあげる。ECO!ECO!<br>
                                    105室は、LANケーブルや電源コードの収納もね<br>         ゴミがないか。機材の破損は無いかなぁチェック。<br>
                                    プリンタの紙、インクをチェック。なかったら本部にメモ残して。次のシフトの方、持っていってあげて。<br>
                                    電気を消して、施錠。
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                貸し出し管理
                            </div>
                            <div class="panel-body">
                                <div>
                                    <div class="col-md-12">
                                    1人1セット。個人が複数台同じものを借りるのはダメ。
                                    </div>
                                    <div class="col-md-offset-1 col-md-8">
                                        サークルやグループで借りるなら、代表者名で貸出処理•責任持ってもらうよ。<br>
                                        (備考にサークルで複数台借りると明記する事)
                                    </div>
                                    <div class="col-md-12">
                                    講義での大量貸出は、先生に責任持ってもらうわ。
                                    </div>
                                    <div class="col-md-offset-1 col-md-8">
                                        ほとんどの場合、SAが借りに来るね。<br>
                                        大量貸出だとQR通すの大変なので、事前にダンボールに入れて、内の1台だけにQR通して管理するなど工夫してっ。
                                    </div>
                                    <div class="col-md-12">
                                    返却時は、必ず中身を確認。
                                    </div>
                                    <div class="col-md-offset-1 col-md-8">
                                        データが残ってないか確認。本人に消してもらうこと。<br>
                                        バッテリー、チャージャー、本体内SDカード。無いと特に困っちゃうものは絶対その場で確認。<br>
                                        混んでないなら全部確認。混んでるならその後すぐ全部みる。いい？必ず確認すること。<br>
                                        無いものがあったら、すぐ確認の連絡をする。放置は絶望的に返ってこないわ。<br>
                                        万が一、備品抜けがでたら、備品が見つかるまで貸し出さないで、隔離してよね。<br>
                                        紛失が確定したら、学籍番号と失くしたであろう備品を記録して情報センターに伝える。一緒に行ってあげて。<br>
                                        講義で使用する場合、データけさんといてってのがあるから、そうしてあげてね。<br>
                                        返却処理したあと、充電を済ませて棚へ。忘れちゃダメ。
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                パスワードの初期化
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    本人じゃなきゃダメ。友達のアカウントを初期化代行ダメ。<br>
                                    初期化したら、初期化後のパスワードについての紙渡して、すぐ変更して使ってくださいって一言を伝える。<br>
                                    (初期パスだと無線LANの認証が通らない、セキュリティ含め)
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                落し物の管理
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                預かり
                                </div>
                                <div class="col-md-offset-1 col-md-8">
                                    学生証、金品、貴重品、パッと見で持ち主が特定できる物、は学生サポートセンターへ
                                    明らかに学校の備品だというものも、ヘルプデスクで落し物として管理する必要無し。<br>
                                    届けてくれた人にはありがとうって、いって。<br>
                                </div>
                                <div class="col-md-12">
                                受け渡し
                                </div>
                                <div class="col-md-offset-1 col-md-8">
                                    私が落としましたって来た学生は、基本的に信じてあげる。<br>
                                    USBメモリは似たようなものが多いので、ファイル名だけ確認。<br>
                                        学生にファイル名の確認だけさせてもらっていいですかって聞く。<br>
                                        どんなファイル保存してましたかとか聞く。<br>
                                        明らかにその学生と違う学籍番号がついたファイル名があったら、その旨を本人に確認してみる。<br>
                                        それでも私のですって言われたらやっぱり信じる。<br>
                                        ファイルの中身は見ちゃダメ。
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                OS,Officeの貸し出し
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    印鑑無くて、でもPC持ってきちゃったなら拇印でも良い。(2013公式)<br>
                                    でも、急ぎじゃないなら印鑑もってまた来て。<br>
                                    申請書は自分で印刷してもってきてもらう。<br>
                                    ディスクは学外持ち出し禁止なので、ヘルプデスクの外の廊下か、寒い季節は実2とかで入れてもいいってことで。<br>
                                    ディスク持ったまま、次の時間講義あるんでとかいって持っていくのはダメ、絶対。
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                iPad貸し出し
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    iPadが当たっている学年の学生が、この後の授業で使うのに充電ピンチやし充電器もっとらんし状態になったときはヘルプデスクで充電を行うために預けることができる。<br>
                                    （これって周知されてるの？？あんまり使われないんだけど…）<br>
                                    カートの上の預り証に、保存用と引換え用の両方に記入してもらい、    引き換え用を学生さんに渡す。保存用はカートの上の「引き換え前」の紙箱に入れる。<br>
                                    預かったiPadはデスクからみて右のカートのスロットに入れて、充電ケーブルを挿しておく。<br>
                                    引き取りにいらっしゃったら引換え用の紙を受け取り、同じ番号のiPadを渡す。<br>
                                    その番号の保存用、引き換え用の紙を両方「引き換え済み」の紙箱に入れる。
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                実習室のトラブルについて
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    対応できないことはすぐに情報センターへ。<br>
                                    授業中でも入っていって構わない。<br>
                                    なにか残すべき情報があるときは、メモを残す。
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                実習室のプリンタの管理
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    用紙切れ対応、トナー交換<br>
                                    用紙やトナーがヘルプデスクに予備無いときは、情報センター経由でEPS室ってところから持っていくことね。
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                        <div class="panel-heading">
                            大事なこと
                        </div>
                        <div class="panel-body">
                            <div class="col-md-12">
                                情報共有すること！<br>
                                なにかあればメーリングに流すこと<br>
                                メモを残しておくこと<br>
                                臨機応変<br>
                                柔軟に対応すること
                            </div>
                            <div class="col-md-offset-1 col-md-8">
                                マニュアル的にダメなことでも、柔軟に対応してあげてもいい。<br>
                                ダメなことはダメだけど、やむを得ないことはあるよね！<br>
                            </div>
                            <div class="col-md-12">
                                学生はわがままなので、ヘルプデスクの仕事じゃないことでも質問に来る。
                            </div>
                            <div class="col-md-offset-1 col-md-8">
                                明らかに講義の内容だったり勉強の話は、eDC3階のチュータをおすすめしてあげる。<br>
                                パソコンのことは、しゃーないので出来る限り対応してあげる。<br>
                                やさしく対応してあげること。
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
