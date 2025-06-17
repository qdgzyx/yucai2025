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
        
        // 获取当前班级的通知公告数据
        $topics = Topic::where('category_id', 2)->paginate(5);
        
        // 修改：获取当前班级在指定日期当天的作业并按学科分组
        $assignments = $banji->assignments()
            ->with(['subject', 'user'])
            ->whereDate('publish_at', $date)   // 只获取指定日期的作业
            ->latest()
            ->take(5)
            ->get();
        
        // 新增：按学科名称分组作业
        $groupedAssignments = $assignments->groupBy('subject.name');

        $reports = $banji->reports()
        ->with('banji') 
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
        $assignments = $banji->assignments()
            ->with(['subject', 'teacher'])
            ->active()
            ->get()
            ->groupBy('subject.name');
            
        return view('banjis.assignmentshow', compact('banji', 'assignments'));
    }
    
    public function create(Banji $banji)
    {
        $grades = \App\Models\Grade::all(); // 获取所有年级数据
        $teachers = User::all(); // 获取所有用户作为教师列表
        return view('banjis.create_and_edit', compact('banji', 'grades', 'teachers')); // 添加teachers参数
    }

	public function store(BanjiRequest $request)
	{
		$banji = Banji::create($request->all());
		return redirect()->route('banjis.show', $banji->id)->with('message', 'Created successfully.');
	}

	public function edit(Banji $banji)
	{
		$this->authorize('update', $banji);
		$grades = \App\Models\Grade::all();
		$teachers = \App\Models\User::all(); // 获取所有用户作为教师列表
		return view('banjis.create_and_edit', compact('banji', 'grades', 'teachers'));
	}

    private function getGroupScoresForBanji(Banji $banji)
    {
        return GroupBasicInfo::leftJoin('group_quantifications', function ($join) {
                $join->on('group_basic_infos.id', '=', 'group_quantifications.group_basic_info_id')
                     ->whereDate('group_quantifications.time', now());
            })
            ->where('group_basic_infos.banji_id', $banji->id)  // 确保使用正确的banji_id字段过滤
            ->selectRaw('group_basic_infos.id as group_basic_info_id, COALESCE(SUM(group_quantifications.score), 0) as total_score')
            ->groupBy('group_basic_info_id')
            ->get()
            ->map(function ($record) {
                $groupInfo = GroupBasicInfo::find($record->group_basic_info_id);
                return [
                    'group_name' => $groupInfo->name,
                    'total_score' => (int)$record->total_score
                ];
            });
    }
}