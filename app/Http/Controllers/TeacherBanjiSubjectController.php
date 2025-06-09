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
        // 修改为获取当前年级全部班级（示例取七年级）
        $banjis = Banji::where('grade_id', '1')->get();
        return view('teacher_banji_subjects.create', compact('teachers', 'subjects', 'banjis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'banji_subjects' => 'required|array',
            'teacher_selection' => 'required|array',
        ]);

        // 从Semester模型获取当前学期
        $semester = Semester::current()->value; // 假设模型有current作用域
        
        foreach ($banjiSubjects as $banjiId => $subjectIds) {
            foreach ($subjectIds as $subjectId) {
                // 获取对应的教师ID
                $teacherId = $teacherSelections[$banjiId][$subjectId] ?? null;

                // 如果教师未选择，则跳过
                if (!$teacherId) {
                    continue;
                }

                // 保存到数据库
                BanjiSubjectTeacher::updateOrCreate(
                    [
                        'banji_id' => $banjiId,
                        'subject_id' => $subjectId,
                        'semester' => $semester
                    ],
                    [
                        'teacher_id' => $teacherId,
                        'semester' => $semester // 存储学期信息
                    ]
                );
            }
        }
    }

    // 新增批量导入方法
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,csv',
        ]);
        
        // 改为使用Semester模型获取当前学期
        $semester = Semester::current()->value;
        Excel::import(new TeacherBanjiSubjectImport($semester), $request->file('import_file'));
        
        return back()->with('success', '批量导入成功');
    }

    public function downloadTemplate()
    {
        // 添加文件存在性检查
        $filePath = public_path('downloads/teacher_banji_subject.XLSX');
        if (!file_exists($filePath)) {
            // 可选：记录日志或返回错误提示
            abort(404, '模板文件未找到');
        }
        return response()->download($filePath, '教师班级学科模板.xlsx');
    }

    public function showForm()
    {
        return view('teacher_banji_subjects.import');
    }
}
