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