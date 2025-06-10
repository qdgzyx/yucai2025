<?php
namespace App\Http\Controllers;
use App\Models\TeacherBanjiSubject;
use App\Models\Subject;
use App\Models\Banji;
use App\Models\User;
use Illuminate\Http\Request;
// 新增 Excel 门面引用
use Maatwebsite\Excel\Facades\Excel;
// 新增 Semester 模型引用
use App\Models\Semester;
// 新增导入类引用
use App\Imports\TeacherBanjiSubjectImport;

class TeacherBanjiSubjectController extends Controller
{
    // 显示所有关联记录
    public function index() 
    {
        $banjis = Banji::with('grade')->get(); // 获取所有班级数据
        
        $query = TeacherBanjiSubject::with(['user', 'subject', 'banji']);
        
        // 添加班级筛选条件
        if (request()->has('banji_filter') && request('banji_filter')) {
            $query->where('banji_id', request('banji_filter'));
        }
        
        $relations = $query->paginate(10);
        
        return view('teacher_banji_subjects.index', compact('relations', 'banjis'));
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
                TeacherBanjiSubject::updateOrCreate( // 修正模型名称
                    [
                        'banji_id' => $banjiId,
                        'subject_id' => $subjectId,
                        'semester' => $semester
                    ],
                    [
                        'teacher_id' => $teacherId
                    ]
                );
            }
        }
    }

    // 新增批量导入方法
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);
        
        try {
            Excel::import(new TeacherBanjiSubjectImport(), $request->file('file'));
            return back()->with('success', '导入成功！');
        } catch (\Exception $e) {
            return back()->with('error', '导入失败: '.$e->getMessage());
        }
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
