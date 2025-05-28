
public function create()
{
    // 添加 $academics 变量，假设从数据库中获取学年数据
    $academics = Academic::all(); // 假设 Academic 是学年的模型
    return view('semesters.create_and_edit', compact('academics'));
}

public function edit(Semester $semester)
{
    // 添加 $academics 变量，假设从数据库中获取学年数据
    $academics = Academic::all(); // 假设 Academic 是学年的模型
    return view('semesters.create_and_edit', compact('semester', 'academics'));
}
