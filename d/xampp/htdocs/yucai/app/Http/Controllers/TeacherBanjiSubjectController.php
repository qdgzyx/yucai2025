public function store(Request $request)
{
    // 验证表单数据
    $request->validate([
        'banji_subjects' => 'required|array',
        'teacher_selection' => 'required|array',
    ]);

    // 获取提交的数据
    $banjiSubjects = $request->input('banji_subjects');
    $teacherSelections = $request->input('teacher_selection');

    // 遍历班级和学科的组合
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
                ],
                [
                    'teacher_id' => $teacherId,
                ]
            );
        }
    }

    // 返回成功响应
    return redirect()->back()->with('success', '数据已成功保存！');
}

public function departmentSchedule(Request $request)
{
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
