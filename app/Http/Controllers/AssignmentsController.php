<?php

namespace App\Http\Controllers;


use App\Models\Assignment;
use App\Models\Banji;
use App\Models\Subject;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // 修正Auth类的引入方式

class AssignmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
	// 教师发布作业表单
    // public function create(Assignment $assignment) 
	// {
    //     $subjects = Subject::all();
    //     $banjis = Banji::where('user_id', auth()->id())->get(); // 仅显示当前教师管理的班级
	public function create()
    {
        try {
            $teacher = auth()->user();
            // 验证用户是否有权限创建作业
            if (!$teacher) {
                abort(403, 'Unauthorized action.');
            }

            $assignment = new Assignment();
            
            // 获取教师教授的科目及其班级
            $subjects = $teacher->taughtSubjects()
                ->with(['banjis' => function($query) use ($teacher) {
                    $query->whereIn('banjis.id', function($q) use ($teacher) {
                        $q->select('banji_id')
                          ->from('teacher_banji_subject')
                          ->where('user_id', $teacher->id);
                    });
                }])
                ->get();

            // 从teacher_banji_subject表获取教师管理的所有班级
            $banjis = Banji::whereIn('id', function($query) use ($teacher) {
                    $query->select('banji_id')
                          ->from('teacher_banji_subject')
                          ->where('user_id', $teacher->id);
                })
                ->with('grade')
                ->get();

            return view('assignments.create', compact('teacher', 'subjects', 'assignment', 'banjis'));
        } catch (\Exception $e) {
            Log::error('Failed to load assignment creation page: '.$e->getMessage());
            abort(500, 'Failed to load assignment creation page. Please try again later.');
        }
    }
    // 保存作业并关联班级
    public function store(Request $request) {
        $assignment = Assignment::create($request->validate([
            'content' => 'required',
            'subject_id' => 'required|exists:subjects,id',
            'publish_at' => 'required|date',
			'deadline' => 'required|date'
        ]) + ['user_id' => auth()->id()]);

        $assignment->banjis()->attach($request->banji_ids); // 关联多个班级
        return redirect()->route('assignments.index', $assignment);
    }

    // 班级作业展示页面
    // public function show(Banji $banji) {
    //     $assignments = $banji->assignments()
    //         ->with('subject')
    //         ->orderBy('deadline')
    //         ->paginate(10);
    //     return view('banjis.assignmentshow', compact('banji', 'assignments'));
    // }

public function show(Banji $banji, Request $request)
{
    // 默认当天或指定日期查询
    $date = $request->input('date', now()->toDateString());
    $banjiId = Auth::user()->banji_id;
    $assignments = $banji->assignments()
        ->with('subject')
        ->whereDate('publish_at', $date)
        ->orderBy('subject_id')
        ->get()
        ->groupBy('subject.name');
    
    return view('banjis.assignmentshow', [
        'banji' => $banji,
        'banjiId'=>$banjiId,
        'date' => $date,
        'groupedAssignments' => $assignments
    ]);
}
	public function index()
	{
		$assignments = Assignment::paginate();
		return view('assignments.index', compact('assignments'));
	}

    // public function show(Assignment $assignment)
    // {
    //     return view('assignments.show', compact('assignment'));
    // }

	// public function create(Assignment $assignment)
	// {
	// 	return view('assignments.create_and_edit', compact('assignment'));
	// }

	// public function store(AssignmentRequest $request)
	// {
	// 	$assignment = Assignment::create($request->all());
	// 	return redirect()->route('assignments.show', $assignment->id)->with('message', 'Created successfully.');
	// }

	public function edit(Assignment $assignment)
	{
        $this->authorize('update', $assignment);
		return view('assignments.create_and_edit', compact('assignment'));
	}

	public function update(AssignmentRequest $request, Assignment $assignment)
	{
		$this->authorize('update', $assignment);
		$assignment->update($request->all());

		return redirect()->route('assignments.show', $assignment->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Assignment $assignment)
	{
		$this->authorize('destroy', $assignment);
		$assignment->delete();

		return redirect()->route('assignments.index')->with('message', 'Deleted successfully.');
	}

    public function approve(Assignment $assignment, Request $request)
    {
        // 添加数据库事务保障数据一致性
        \DB::transaction(function () use ($assignment, $request) {
            $assignment->update([
                'status' => 'approved',
                // 'approval_type' => $request->input('type', 'group'), // 修正参数获取方式
                'approval_time' => now(), // 新增审批时间字段
                'reject_reason' => null // 清空驳回原因
            ]);
        });
    
        return back()->with('success', '作业已通过审核');
    }

    public function reject(Assignment $assignment, Request $request)
    {
        

        \DB::transaction(function () use ($assignment, $request) {
            $assignment->update([
                'status' => 'rejected',
                // 'approval_type' => $request->input('type', 'group'),
                // 'reject_reason' => $request->input('reason'),
                'approval_time' => now(), // 新增驳回时间字段
                'reject_reason' => null // 清空驳回原因
            ]);
        });

        return back()->with('error', '作业已驳回');
    }
}