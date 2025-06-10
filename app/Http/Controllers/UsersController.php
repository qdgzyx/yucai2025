<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Banji;
use App\Models\Subject;
use App\Models\TeacherBanjiSubject;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        $banjis = Banji::all();
        $subjects = Subject::all();
        return view('users.edit', compact('user', 'banjis', 'subjects'));
    }
    // public function update(UserRequest $request, User $user)
    // {
    //     $user->update($request->all());
    //     return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    // }
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->all();

        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id );
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
    public function showForm()
    {
        return view('users.import');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return back()->with('success', '数据导入成功！');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', '导入失败: ' . $e->getMessage());
        }
    }
    public function teachingSchedule(User $user)
    {
    $banjis = Banji::with('teacherBanjiSubjects.teacher')->get();
    $subjects = Subject::all();
    return view('users.teaching_schedule', compact('user', 'banjis', 'subjects'));
    }
    // 需要确保存在此方法
    public function downloadTemplate()
    {
        $filePath = public_path('downloads/user_template.xlsx');
        if (!file_exists($filePath)) {
            abort(404, '模板文件未找到');
        }
        return response()->download($filePath, '用户信息模板.xlsx');
    }
}