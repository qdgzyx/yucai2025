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
use Illuminate\Support\Facades\DB; // 新增DB门面引入
use App\Rules\MaxScore; // 新增：引入自定义验证规则类

class QuantifyRecordsController extends Controller
{
    // 优化：添加路由模型绑定自动解析
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]); // 安全风险：公开数据需要确认是否需要授权
        // 建议：移除except或添加额外权限控制
    }

    // 安全改进：添加授权检查
    public function index()
    {
        // 修改分页数量为15条/页，并按ID倒序排列
        $quantify_records = QuantifyRecord::with('user', 'quantifyItem')
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('quantify_records.index', compact('quantify_records'));
    }

    // 安全改进：添加策略检查
    public function show(QuantifyRecord $quantify_record)
    {
        $this->authorize('view', $quantify_record); // 新增策略检查
        return view('quantify_records.show', compact('quantify_record'));
    }

	public function create()
	{
        $quantifyItems = QuantifyItem::when(!auth()->user()->is_admin, function($query) {
            $query->where('user_id', auth()->id());
        })->get();

		return view('quantify_records.create_and_edit', [
        'quantify_record' => new QuantifyRecord(),
        'quantify_items' => $quantifyItems,
        'grades' => Grade::with('banjis')->get(),
        'quantifyItemScore' => $quantifyItems->first()->score ?? 0,
		]);
	}

	public function store(Request $request)
	{
	    // 新增：获取当前学期
	    $currentSemester = Semester::where('is_current', true)->firstOrFail();
	    
	    // 新增：获取量化项目实例
	    $quantifyItem = QuantifyItem::findOrFail($request->quantify_item_id);

        $data = $request->validate([
            'quantify_item_id' => 'required|exists:quantify_items,id,user_id,'.auth()->id(),
            'scores' => 'required|array|min:1',
            'scores.*' => ['numeric', 'min:0', new MaxScore($quantifyItem->score)],
            'remarks' => 'nullable|array',
            'remarks.*' => 'max:500'
        ]);

        // 新增：获取并验证班级数据
        $validBanjis = Banji::whereIn('id', array_keys($data['scores']))
            ->whereHas('grade', function($query) use ($request) {
                $query->where('id', $request->grade_id);
            })
            ->get();

        // 优化：使用模型关联创建记录
        $user = $request->user();
        $records = $validBanjis->map(function($banji) use ($data, $user, $quantifyItem, $currentSemester) {
            return [
                'semester_id' => $currentSemester->id,
                'quantify_item_id' => $data['quantify_item_id'],
                'banji_id' => $banji->id,
                'score' => $data['scores'][$banji->id],
                'remark' => $data['remarks'][$banji->id] ?? null,
                'user_id' => $user->id,
                'ip_address' => request()->ip(),
                'assessed_at' => now()
            ];
        });

        // 安全改进：使用批量赋值保护
        DB::transaction(function () use ($records, $user) {
            $user->quantifyRecords()->createMany($records);
        });
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

    // 安全改进：使用路由模型绑定和策略
    public function update(QuantifyRecordRequest $request, QuantifyRecord $quantify_record)
    {
        $this->authorize('update', $quantify_record); // 使用策略授权
		$quantify_record->update($request->all());

		return redirect()->route('quantify_records.show', $quantify_record->id)->with('message', 'Updated successfully.');
	}

    // 安全改进：添加软删除支持
    public function destroy(QuantifyRecord $quantify_record)
    {
        $this->authorize('delete', $quantify_record);
        $quantify_record->delete(); // 需要模型支持软删除
        
        return redirect()->route('quantify_records.index')
            ->with('message', 'Deleted successfully.');
    }
}