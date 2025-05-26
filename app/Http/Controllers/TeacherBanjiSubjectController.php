<?php
namespace App\Http\Controllers;
use App\Models\TeacherBanjiSubject;
use App\Models\Subject;
use App\Models\Banji;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherBanjiSubjectController extends Controller
{
    // 显示所有关联记录
    public function index() 
    {
        $relations = TeacherBanjiSubject::with(['user', 'subject', 'banji'])->paginate(10);
        return view('teacher_banji_subjects.index', compact('relations'));
    }

    // 显示创建表单
    public function create()
    {
        $teachers = User::all();
        $subjects = Subject::all();
        $banjis = Banji::all();
        return view('teacher_banji_subjects.create', compact('teachers', 'subjects', 'banjis'));
    }

    // 存储新关联
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'banji_id' => 'required|exists:banjis,id'
        ]);

        \DB::table('teacher_banji_subject')->insert($request->only(['user_id', 'subject_id', 'banji_id']));
        
        return redirect()->route('teacher-banji-subjects.index')
                         ->with('success', '关联关系创建成功');
    }
    public function destroy()
	{
		$this->authorize('destroy', $teacher-banji-subject);
		$report->delete();

		return redirect()->route('teacher-banji-subjects.index')->with('message', 'Deleted successfully.');
	}
}