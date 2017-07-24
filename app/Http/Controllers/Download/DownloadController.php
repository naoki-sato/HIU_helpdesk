<?php

namespace App\Http\Controllers\Download;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{


    /**
     * DownloadController constructor.
     */
    public function __construct()
    {
        // ディレクトリがなければ，作成
        if(!file_exists(storage_path('app/public/etc/'))){

            Storage::makeDirectory('/etc/');
        }

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $directory = 'etc/';

        $files =  Storage::allFiles($directory);


        // 余計なURLパスまであるので，最後だけ取り出す
        foreach ($files as $key => $file) {
            $files[$key] = basename($file);
        }

        return view('management/etc/index', ['files' => $files]);
    }


    /**
     * ファイルダウンロード
     * @param $file
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function show($file)
    {

        $file_path = storage_path('app/public/etc/') . $file;

        // ファイルがない場合は，リダイレクト
        if(!file_exists($file_path)) {

            session()->flash('alert_message', '<h3>ファイルが存在しません</h3>');
            return redirect()->back();
        }

        return response()->download($file_path);
    }


    /**
     * ファイルの削除
     * @param $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $file)
    {



        // アクセス権の確認
        if(!in_array($request->user()['role'], ['admin', 'manager'])) {
            session()->flash('alert_message', '<h3>アクセス権がありません</h3>');
            return redirect()->back();
        }


        $directory = '/etc/';

        $file_path = $directory . $file;

        // ファイルがない場合は，リダイレクト
        if(!file_exists(storage_path('app/public') . $file_path)) {

            session()->flash('alert_message', '<h3>不正なPOSTです</h3>');
            return redirect()->back();
        }

        $id_delete =  Storage::delete($file_path);

        // 指定されたファイルの削除が成功したかどうか
        if($id_delete) {

            session()->flash('success_message', '<h3>'.$file.'を削除しました</h3>');

        } else {

            session()->flash('alert_message', '<h3>ファイルの削除に失敗しました</h3>');
        }

        return redirect()->back();

    }


    /**
     *
     * ファイルのアップロード
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {


        // アクセス権の確認
        if(!in_array($request->user()['role'], ['admin', 'manager'])) {
            session()->flash('alert_message', '<h3>アクセス権がありません</h3>');
            return redirect()->back();
        }

        // バリデーション
        $this->validate($request, ['file_input'  => 'required']);

        try {

            // 画像がポストされていた場合
            if(Input::hasFile('file_input')){

                $file = $request['file_input'];

                // ファイルをストレージに保存
                Storage::put('/etc/' . $file->getClientOriginalName(), $file);
            }

        } catch(\Exception $e) {

            session()->flash('alert_message', '<h3>アップロード失敗しました。</h3>');
            return redirect()->back();
        }

        session()->flash('success_message', '<h3>アップロード成功しました。</h3>');
        return redirect()->back();

    }

}
