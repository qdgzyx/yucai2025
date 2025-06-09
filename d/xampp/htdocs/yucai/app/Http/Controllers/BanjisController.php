public function create(Banji $banji)
{
    $grades = \App\Models\Grade::all();
    $teachers = \App\Models\User::all(); // 新增：获取所有用户作为教师列表
    return view('banjis.create_and_edit', compact('banji', 'grades', 'teachers'));
}

public function edit(Banji $banji)
{
    $this->authorize('update', $banji);
    $grades = \App\Models\Grade::all();
    $teachers = \App\Models\User::all(); // 新增：获取所有用户作为教师列表
    return view('banjis.create_and_edit', compact('banji', 'grades', 'teachers'));
}

public function downloadTemplate()
{
    $filePath = public_path('downloads/banji_template.xlsx');
    if (!file_exists($filePath)) {
        abort(404, '模板文件未找到');
    }
    return response()->download($filePath, '班级信息模板.xlsx');
}