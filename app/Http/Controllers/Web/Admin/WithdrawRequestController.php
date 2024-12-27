<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;

class WithdrawRequestController extends Controller
{
    public function index()
    {
        $withdrawRequests = WithdrawRequest::with(['user', 'account'])
            ->orderByRaw("FIELD(status, 'pending') DESC") // Сначала 'pending', затем остальные
            ->orderBy('created_at', 'desc') // Дополнительная сортировка по дате создания
            ->paginate(15);

        return view('admin.withdraw_requests.index', compact('withdrawRequests'));
    }


    public function update(Request $request, $id)
    {
        $withdrawRequest = WithdrawRequest::findOrFail($id);
        $withdrawRequest->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Статус обновлен.');
    }
}
