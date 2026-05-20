<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Banji;
use App\Models\Topic;
use App\Models\Assignment;
use App\Models\Record; // 添加: 引入 Record 模型
use App\Models\GroupQuantification;
use App\Models\GroupBasicInfo; // 确保引入GroupBasicInfo模型
use App\Models\Report;
use Illuminate\Http\Request;
use App\Imports\BanjisImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Requests\BanjiRequest;
use App\Models\User;

class BanjisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'assignmentshow']]);
    }

	public function index()
	{
		$banjis = Banji::with('grade', 'user')->paginate(10);
		return view('banjis.index', compact('banjis'));
	}

    public function show(Banji $banji)
    {
        // 获取日期参数或使用当前日期作为默认值
        $date = request('date', now()->toDateString());
        
        // 获取当前班级的通知公告数据（优化：添加缓存）
        $topics = cache()->remember("topics_category_2_page_" . request('page', 1), 300, function () {
            return Topic::where('category_id', 2)->paginate(5);
        });
        
        // 修改：获取当前班级在指定日期当天的作业并按学科分组（优化：字段选择）
        $assignments = $banji->assignments()
            ->with(['subject:id,name', 'user:id,name']) // 优化：只加载需要的字段
            ->whereDate('publish_at', $date)   // 只获取指定日期的作业
            ->latest()
            ->take(5)
            ->get();
        
        // 新增：按学科名称分组作业
        $groupedAssignments = $assignments->groupBy('subject.name');

        // 优化：只查询当天报告并限制字段
        $reports = $banji->reports()
            ->select('id', 'banji_id', 'date', 'total_expected', 'total_actual') // 优化：字段选择
            ->whereDate('date', $date) 
            ->get();
        
        // 修改：使用正确的关联方法 groupQuantifications()
        $groupScores = $this->getGroupScoresForBanji($banji);

        return view('banji.show', compact(
            'banji', 
            'topics', 
            'assignments', 
            'date', 
            'groupedAssignments',
            'reports',
            'groupScores'
        ));
    }
    
    public function assignmentshow(Banji $banji) { 
        // 优化：添加字段选择和预加载
        $assignments = $banji->assignments()
            ->with(['subject:id,name', 'user:id,name']) // 优化：只加载需要的字段
            ->select('id', 'subject_id', 'user_id', 'content', 'publish_at', 'deadline') // 优化：字段选择
            ->where('status', 'approved') // 假设 active 是 scope，如果没有则使用状态过滤
            ->get()
            ->groupBy('subject.name');
            
        return view('banjis.assignmentshow', compact('banji', 'assignments'));
    }
    
    public function create(Banji $banji)
    {
        // 优化：只查询需要的字段并缓存
        $grades = cache()->remember('grades_id_name', 3600, function () {
            return \App\Models\Grade::select('id', 'name')->get();
        });
        $teachers = cache()->remember('users_for_banji', 3600, function () {
            return User::select('id', 'name')->get();
        });
        return view('banjis.create_and_edit', compact('banji', 'grades', 'teachers'));
    }

	public function store(BanjiRequest $request)
	{
		$banji = Banji::create($request->all());
		return redirect()->route('banjis.show', $banji->id)->with('message', 'Created successfully.');
	}

	public function edit(Banji $banji)
	{
		$this->authorize('update', $banji);
		// 优化：使用缓存
		$grades = cache()->remember('grades_id_name', 3600, function () {
            return \App\Models\Grade::select('id', 'name')->get();
        });
		$teachers = cache()->remember('users_for_banji', 3600, function () {
            return User::select('id', 'name')->get();
        });
		return view('banjis.create_and_edit', compact('banji', 'grades', 'teachers'));
	}

    private function getGroupScoresForBanji(Banji $banji)
    {
        // 优化：使用单次查询并预加载数据
        return cache()->remember("group_scores_banji_{$banji->id}_" . now()->toDateString(), 300, function () use ($banji) {
            return GroupBasicInfo::leftJoin('group_quantifications', function ($join) {
                    $join->on('group_basic_infos.id', '=', 'group_quantifications.group_basic_info_id')
                         ->whereDate('group_quantifications.time', now());
                })
                ->where('group_basic_infos.banji_id', $banji->id)
                ->select('group_basic_infos.id as group_basic_info_id', 'group_basic_infos.name', \DB::raw('COALESCE(SUM(group_quantifications.score), 0) as total_score'))
                ->groupBy('group_basic_infos.id', 'group_basic_infos.name')
                ->get()
                ->map(function ($record) {
                    return [
                        'group_name' => $record->name,
                        'total_score' => (int)$record->total_score
                    ];
                });
        });
    }
}