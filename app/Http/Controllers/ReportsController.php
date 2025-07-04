<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Banji;
use App\Models\Grade;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index()
	{
		$reports = Report::paginate();
		return view('reports.index', compact('reports'));
	}

    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

	// public function create(Report $report)
	// {
	// 	return view('reports.create_and_edit', compact('report'));
	// }
	public function create(Report $report)
	{
    // 修改为通过班级表的 user_id 字段查询当前用户管理的班级
    $currentBanji = Banji::where('user_id', Auth::id())->first();

    return view('reports.create_and_edit', [ 
		'report' => new Report(),
        'banjis' => Banji::all(),
        'currentBanji' => $currentBanji
    ]);
	}
	
public function store(ReportRequest $request)
{
    // 获取当前用户的班级ID（需确保用户模型与班级关联）
    $banjiId = Auth::user()->banji_id; 
    
    // 合并到请求数据中
    $request->merge(['banji_id' => $banjiId]);

    $report = Report::create($request->all());
    
    return redirect()->route('reports.show', $report->id)
                     ->with('message', 'Created successfully.');
}
	// public function edit(Report $report)
	// {
    //     $this->authorize('update', $report);
	// 	return view('reports.create_and_edit', compact('report'));
	// }

	public function edit(Report $report) // 路由模型绑定
{
    $this->authorize('update', $report);
    $currentBanji = Auth::user()->banji;
    return view('reports.edit', [
        'report' => $report,
        'banjis' => Banji::all(),
        'currentBanji' => $currentBanji
    ]);
}

	public function update(ReportRequest $request, Report $report)
	{
		$this->authorize('update', $report);
		$report->update($request->all());

		return redirect()->route('reports.show', $report->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Report $report)
	{
		$this->authorize('destroy', $report);
		$report->delete();

		return redirect()->route('reports.index')->with('message', 'Deleted successfully.');
	}
	// // ReportController.php
	// public function summary()
	// {
    // // 获取当天日期（2025-05-19）
    // $today = now()->format('Y-m-d');
   
    // // 预加载班级关联，按班级分组统计（假设Report模型已定义与Banji的belongsTo关联）
    // $banjis = Report::with('banji')
    //     ->whereDate('date', $today)
    //     ->selectRaw('banji_id,
    //         SUM(total_expected) as total_expected,
    //         SUM(total_actual) as total_actual,
    //         SUM(sick_leave_count) as sick_leave_count,
    //         GROUP_CONCAT(sick_list) as sick_list_all,
    //         SUM(personal_leave_count) as personal_leave_count,
    //         GROUP_CONCAT(personal_list) as personal_list_all,
    //         SUM(absent_count) as absent_count')
    //     ->groupBy('banji_id')
    //     ->get();

    // // 计算合计行数据
    // $totals = [
    //     'total_expected' => $banjis->sum('total_expected'),
    //     'total_actual' => $banjis->sum('total_actual'),
    //     'sick_leave_count' => $banjis->sum('sick_leave_count'),
    //     'personal_leave_count' => $banjis->sum('personal_leave_count'),
    //     'absent_count' => $banjis->sum('absent_count')
    // ];

    // return view('reports.summary', compact('banjis', 'today', 'totals'));
	// }

	// ReportController.php
	public function summary()
	{
    $today = now()->format('Y-m-d');

    // 获取每个班级当天最新的报告ID
    $latestReportIds = Report::selectRaw('MAX(id) as latest_id, banji_id')
        ->whereDate('date', $today)
        ->groupBy('banji_id')
        ->pluck('latest_id');

    // 根据最新ID获取完整数据
    $banjis = Report::with('banji')
        ->whereIn('id', $latestReportIds)
        ->get();

    // 计算合计
    $totals = [
        'total_expected' => $banjis->sum('total_expected'),
        'total_actual' => $banjis->sum('total_actual'),
        'sick_leave_count' => $banjis->sum('sick_leave_count'),
        'personal_leave_count' => $banjis->sum('personal_leave_count'),
        'absent_count' => $banjis->sum('absent_count')
    ];

    return view('reports.summary', compact('banjis', 'today', 'totals'));
    }

    public function summaryByGrade($grade_id, Request $request)
    {
    // 获取当前年级
    $currentGrade = Grade::find($grade_id)->name;

    // 获取所有班级
    $allBanji = Banji::where('grade_id', $grade_id)->orderBy('name')->get(); // 修改：按班级名称排序

    // 获取已提交的班级数据
    $selectedDate = $request->input('date', now()->toDateString());
    $banjis = Report::where('date', $selectedDate)
                    ->whereHas('banji', function ($query) use ($grade_id) {
                        $query->where('grade_id', $grade_id);
                    })
                    ->with('banji')
                    ->get();

    return view('reports.summary', compact('allBanji', 'banjis', 'currentGrade', 'grade_id', 'selectedDate'));
    }

    public function exportByGrade($grade_id)
    {
        $grade = Grade::findOrFail($grade_id);
        $data = $this->getGradeData($grade_id);
        
        return Excel::download(new ReportExport($data), "{$grade->name}出勤汇总.xlsx");
    }

    private function getGradeData($grade_id)
    {
        // 复用summaryByGrade中的查询逻辑
        return [
            'allBanji' => Banji::where('grade_id', $grade_id)->get(),
            'banjis' => Report::whereHas('banji', fn($q) => $q->where('grade_id', $grade_id))->get(),
            'gradeName' => Grade::find($grade_id)->name
        ];
    }

}