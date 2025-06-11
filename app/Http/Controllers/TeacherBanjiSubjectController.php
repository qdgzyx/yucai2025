<?php
namespace App\Http\Controllers;
use App\Models\TeacherBanjiSubject;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Banji;
use App\Models\User;
use Illuminate\Http\Request;
// 新增 Excel 门面引用
use Maatwebsite\Excel\Facades\Excel;
// 新增 Semester 模型引用
use App\Models\Semester;
// 新增导入类引用
use App\Imports\TeacherBanjiSubjectImport;

use App\Exports\TeacherBanjiSubjectExport;


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

        // 获取当前学期ID
        $currentSemester = Semester::current()->id;

        $banjiSubjects = $request->input('banji_subjects');
        $teacherSelections = $request->input('teacher_selection');

        foreach ($banjiSubjects as $banjiId => $subjectIds) {
            foreach ($subjectIds as $subjectId) {
                $teacherId = $teacherSelections[$banjiId][$subjectId] ?? null;
                if (!$teacherId) continue;

                TeacherBanjiSubject::updateOrCreate(
                    [
                        'banji_id' => $banjiId,
                        'subject_id' => $subjectId,
                        'semester_id' => $currentSemester // 改为使用semester_id字段
                    ],
                    ['user_id' => $teacherId]
                );
            }
        }

        return redirect()->back()->with('success', '数据已成功保存！');
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

    public function departmentSchedule(Request $request)
    {
        // 新增学期存在性校验
        $selectedSemester = $request->input('semester_filter');
        if ($selectedSemester && !Semester::find($selectedSemester)) {
            return back()->withErrors(['semester_filter' => '无效的学期选择']);
        }

        // 修改查询逻辑添加学期存在性检查
        $query = TeacherBanjiSubject::with(['user', 'subject', 'banji'])
            ->when($selectedSemester, function ($query, $semester) {
                return $query->where('semester_id', $semester);
            });

        // 获取所有学期
        $semesters = Semester::all();

        // 获取所有级部（假设有一个 Grade 模型）
        $grades = Grade::all();

        // 当前选择的学期和级部
        $selectedSemester = $request->input('semester_filter');
        $selectedGrade = $request->input('grade_filter');

        // 查询教师安排
        $query = TeacherBanjiSubject::with(['user', 'subject', 'banji']);

        // 根据学期筛选
        if ($selectedSemester) {
            $query->where('semester_id', $selectedSemester);
        }

        // 根据级部筛选（假设 banji 模型中有 grade_id 字段）
        if ($selectedGrade) {
            $query->whereHas('banji', function ($q) use ($selectedGrade) {
                $q->where('grade_id', $selectedGrade);
            });
        }

        $schedules = $query->get();

        // 获取所有学科
        $subjects = Subject::all();

        // 获取所有班级（根据选中的级部过滤）
        $banjis = Banji::when($selectedGrade, function ($query, $grade) {
            $query->where('grade_id', $grade);
        })->get();

        return view('teacher_banji_subjects.department_teacher_schedule', compact('semesters', 'grades', 'schedules', 'selectedSemester', 'selectedGrade', 'subjects', 'banjis'));
}


    // 添加导出 Excel 的方法
    public function export()
    {
        // 获取当前学期ID
        $currentSemester = Semester::current()->id;

        // 查询数据并传递给导出类
        $schedules = TeacherBanjiSubject::with(['banji', 'subject', 'user'])
            ->where('semester_id', $currentSemester)
            ->get();

        return Excel::download(new TeacherBanjiSubjectExport($schedules), '教师班级学科关联.xlsx');
    }
}
