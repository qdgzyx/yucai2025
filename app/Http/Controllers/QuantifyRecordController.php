<?php

namespace App\Http\Controllers;

use App\Models\QuantifyRecord;
use App\Models\QuantifyItem;
use Illuminate\Http\Request;

class QuantifyRecordController extends Controller
{
    public function store(Request $request)
    {
        $item = QuantifyItem::findOrFail($request->quantify_item_id);
        
        // 添加管理员权限检查
        if (!auth()->user()->is_admin && $item->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $record = QuantifyRecord::create($request->all());
        
        return redirect()->back();
    }

    public function update(Request $request, QuantifyRecord $record)
    {
        // 添加管理员权限检查
        if (!auth()->user()->is_admin && $record->quantifyItem->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $record->update($request->all());
        
        return redirect()->back();
    }
}