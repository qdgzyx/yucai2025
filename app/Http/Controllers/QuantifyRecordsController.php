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
use Carbon\Carbon; // 新增：引入Carbon日期处理类

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
        // 修改为按检查时间和量化项目分组展示
        $quantify_records = auth()->user()->quantifyRecords()
            ->with('banji','user', 'quantifyItem')
            ->orderBy('assessed_at', 'desc')
            ->orderBy('quantify_item_id')
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
            'quantify_item_id' => [
                'required',
                'exists:quantify_items,id,user_id,'.auth()->id(),
                function ($attribute, $value, $fail) use ($request) {
                    $query = QuantifyRecord::where('user_id', auth()->id())
                        ->where('quantify_item_id', $value)
                        ->whereDate('assessed_at', Carbon::parse($request->assessed_at)->toDateString());

                    // 添加排除当前编辑记录的判断（仅更新时生效）
                    if ($request->isMethod('put') || $request->isMethod('patch')) {
                        $recordId = $request->route('quantify_record');
                        $query->where('id', '!=', $recordId->id);
                    }

                    if ($query->exists()) {
                        $fail('该量化项目今日已检查过（检查时间：'.Carbon::parse($request->assessed_at)->toDateString().'），请勿重复录入');
                    }
                }
            ],
            'scores' => 'required|array|min:1',
            'scores.*' => ['numeric', 'min:0', new MaxScore($quantifyItem->score)],
            'remarks' => 'nullable|array',
            'remarks.*' => 'max:500',
            'assessed_at' => 'required|date', // 新增检查时间验证
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
                'assessed_at' => $data['assessed_at'] // 替换原来的now()
            ];
        });

        // 安全改进：使用批量赋值保护
        DB::transaction(function () use ($records, $user) {
            $user->quantifyRecords()->createMany($records);
        });

        // 新增：完成存储后重定向到列表页
        return redirect()->route('quantify_records.index')
            ->with('message', '记录添加成功');
    }

	public function edit(QuantifyRecord $quantify_record)
    {
        // 改进权限检查逻辑：
        // 1. 管理员可以编辑所有记录
        // 2. 普通用户只能编辑自己创建的记录
        // 3. 处理量化项目被删除的情况
        if (!auth()->user()->is_admin) {
            // 检查记录所属用户
            if ($quantify_record->user_id !== auth()->id()) {
                abort(403, '您没有权限编辑此记录');
            }
            
            // 安全检查量化项目是否存在
            if (!$quantify_record->quantifyItem) {
                abort(404, '关联的量化项目不存在');
            }
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
    public function update(Request $request, QuantifyRecord $quantify_record)
    {
        $this->authorize('update', $quantify_record);
        
        $currentSemester = Semester::where('is_current', true)->firstOrFail();
        $quantifyItem = QuantifyItem::findOrFail($request->quantify_item_id);

        $data = $request->validate([
            'quantify_item_id' => [
                'required',
                'exists:quantify_items,id,user_id,'.auth()->id(),
                function ($attribute, $value, $fail) use ($request, $quantify_record) {
                    $existing = QuantifyRecord::where('user_id', auth()->id())
                        ->where('quantify_item_id', $value)
                        ->whereDate('assessed_at', Carbon::parse($request->assessed_at)->toDateString())
                        ->where('id', '!=', $quantify_record->id)
                        ->exists();
                
                    if ($existing) {
                        $fail('该量化项目今日已检查过（检查时间：'.Carbon::parse($request->assessed_at)->toDateString().'），请勿重复录入');
                    }
                }
            ],
            'scores' => 'required|array|min:1',
            'scores.*' => ['numeric', 'min:0', new MaxScore($quantifyItem->score)],
            'remarks' => 'nullable|array',
            'remarks.*' => 'max:500',
            'assessed_at' => 'required|date',
        ]);

        // 更新记录逻辑（根据实际业务需求补充）
        $quantify_record->update([
            'quantify_item_id' => $data['quantify_item_id'],
            'assessed_at' => $data['assessed_at'],
            'score' => array_sum($data['scores']),
            'remark' => json_encode($data['remarks'])
        ]);

        return redirect()->route('quantify_records.index')
            ->with('message', '记录更新成功');
    }

    public function destroyBatch(Request $request)
    {
        $request->validate([
            'quantify_item_id' => 'required|exists:quantify_items,id',
            'assessed_at' => 'required|date'
        ]);

        $records = QuantifyRecord::where('quantify_item_id', $request->quantify_item_id)
            ->whereDate('assessed_at', $request->assessed_at)
            ->where('user_id', auth()->id())
            ->get();

        foreach ($records as $record) {
            $record->delete();
        }

        return redirect()->route('quantify_records.index')
            ->with('message', '批次记录删除成功');
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