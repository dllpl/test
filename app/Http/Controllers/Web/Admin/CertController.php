<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;

class CertController extends Controller
{

    public function index()
    {

        $cert_count = \DB::table('request_to_super as s')
            ->join('users as u', 's.user_id', '=', 'u.id')
            ->select('s.id as s_id', 'u.phone', 'u.email', 'u.name', 's.status', 's.created_at as s_created_at')
            ->orderByDesc('s.created_at')
            ->get();

        return view('admin.cert', ['data' => $cert_count]);
    }

    public function action($action, $request_id)
    {
        $query = \DB::table('request_to_super')->where('id', $request_id);

        switch ($action) {
            case 'accept':
                $query->update(['status' => 1]);
                $msg = "Заявка №$request_id. Успешно принята.";
                break;
            case 'decline':
                $query->update(['status' => 2]);
                $msg = "Заявка №$request_id. Успешно отказана.";
                break;
            case 'rollback':
                $query->update(['status' => 0]);
                $msg = "Заявка №$request_id. Возвращена в обработку.";
            default:
                $msg = 'Неизвестный статус';
        }

        $cert_count = \DB::table('request_to_super as s')
            ->join('users as u', 's.user_id', '=', 'u.id')
            ->select('s.id as s_id', 'u.phone', 'u.email', 'u.name', 's.status', 's.created_at as s_created_at')
            ->orderByDesc('s.created_at')
            ->get();

        return back()->with('msg', $msg);
    }
}
