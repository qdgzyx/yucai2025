<?php

namespace App\Http\Controllers;

use App\Models\QuantifyRecord;
use App\Models\QuantifyItem;
use App\Models\Grade;
use Illuminate\Http\Request;
use App\Models\Banji;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\QuantifyType;
use App\Models\Semester;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuantifyRecordRequest;

class QuantifyRecordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index()
    {
        // 恢复原始列表查询逻辑
        $quantify_records = QuantifyRecord::with('user', 'quantifyItem')->paginate();
        return view('quantify_records.index', compact('quantify_records'));
    }

    public function show(QuantifyRecord $quantify_record)
    {
        return view('quantify_records.show', compact('quantify_record'));
    }

	public function create()
	{
		return view('quantify_records.create_and_edit', [
        'quantify_record' => new QuantifyRecord(),
        'quantify_items' => QuantifyItem::when(!auth()->user()->is_admin, function($query) {
            $query->where('user_id', auth()->id());
        })->get(),
        'grades' => Grade::with('banjis')->get(),
		'quantifyItemScore' => QuantifyItem::first()->score ?? 0,
        'semesters' => Semester::where('is_current', true)->get()
    ]);
	}

	public function store(Request $request)
	{
    $data = $request->validate([
        'semester_id' => 'required|exists:semesters,id',
        'quantify_item_id' => 'required|exists:quantify_items,id', 
        'grade_id' => 'required|exists:grades,id',
        'scores' => 'required|array',
        'remarks' => 'nullable|array'
    ]);

    // 添加权限验证
    $quantifyItem = QuantifyItem::findOrFail($data['quantify_item_id']);
    if (!auth()->user()->is_admin && $quantifyItem->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    // 获取量化项目最大分值
    $quantifyItem = QuantifyItem::findOrFail($data['quantify_item_id']);
    $maxScore = $quantifyItem->score;

    foreach ($data['scores'] as $banjiId => $score) {
        // 验证分数不超过最大值
        if ($score > $maxScore) {
            return back()
                ->withInput()
                ->withErrors(['scores.'.$banjiId => "分数不能超过该项目的最大值({$maxScore})"]);
        }

        QuantifyRecord::create([
            'semester_id' => $data['semester_id'],
            'quantify_item_id' => $data['quantify_item_id'],
            'banji_id' => $banjiId,
            'score' => $score,
            'remark' => $data['remarks'][$banjiId] ?? null,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip()
        ]);
    }

    return redirect()->route('quantify_records.index')->with('success', '量化记录已保存');
	}

	public function edit(QuantifyRecord $quantify_record)
    {
        if (!auth()->user()->is_admin && $quantify_record->quantifyItem->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('quantify_records.create_and_edit', [
            'quantify_record' => $quantify_record,
            'quantify_items' => QuantifyItem::when(!auth()->user()->is_admin, function($query) {
                $query->where('user_id', auth()->id());
            })->get(),
            // 修正：使用当前记录对应的项目分数
            'quantifyItemScore' => $quantify_record->quantifyItem->score ?? 0,
            'grades' => Grade::with('banjis')->get(),
            'semesters' => Semester::where('is_current', true)->get()
        ]);
	}

	public function update(QuantifyRecordRequest $request, QuantifyRecord $quantify_record)
	{
        if (!auth()->user()->is_admin && $quantify_record->quantifyItem->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $this->authorize('update', $quantify_record);
		$quantify_record->update($request->all());

		return redirect()->route('quantify_records.show', $quantify_record->id)->with('message', 'Updated successfully.');
	}

	public function destroy(QuantifyRecord $quantify_record)
	{
		$this->authorize('destroy', $quantify_record);
		$quantify_record->delete();

		return redirect()->route('quantify_records.index')->with('message', 'Deleted successfully.');
	}

}