<?php

namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\Banji;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignmentRequest;

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
    //     return view('assignments.create', compact('subjects', 'banjis','assignment'));
    // }
	public function create()
	{
		$assignment = new Assignment(); 
        $teacher = auth()->user(); 
    	$subjects = $teacher->taughtSubjects()
    ->with(['banjis' => function($query) use ($teacher) {
        $query->where('banjis.user_id', $teacher->id); 
    }])->get();
    	$banjis = Banji::where('user_id', $teacher->id)->get();

    return view('assignments.create', compact('teacher', 'subjects', 'banjis','assignment'));
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
        return redirect()->route('assignments.show', $assignment);
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
}