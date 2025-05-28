<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\Academic;
use App\Http\Requests\SemesterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SemestersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function create(): View
    {
        $this->authorize('create', Semester::class);
        
        return view('semesters.create_and_edit', [
            'semester' => new Semester(),
            'academics' => Academic::select('id', 'name')->orderBy('name')->get()
        ]);
    }

    public function store(SemesterRequest $request): RedirectResponse
    {
        try {
            $semester = Semester::create($request->validated());
            
            // 如果设置为当前学期，更新其他学期为非当前
            if ($request->boolean('is_current')) {
                Semester::where('id', '!=', $semester->id)
                    ->update(['is_current' => false]);
            }

            return redirect()
                ->route('semesters.show', $semester->id)
                ->with('success', '学期创建成功');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => '创建失败: '.$e->getMessage()]);
        }
    }

    public function edit(Semester $semester): View
    {
        $this->authorize('update', $semester);

        return view('semesters.create_and_edit', [
            'semester' => $semester,
            'academics' => Academic::select('id', 'name')->orderBy('name')->get()
        ]);
    }

    public function update(SemesterRequest $request, Semester $semester): RedirectResponse
    {
        $this->authorize('update', $semester);

        try {
            $semester->update($request->validated());
            
            // 如果设置为当前学期，更新其他学期为非当前
            if ($request->boolean('is_current')) {
                Semester::where('id', '!=', $semester->id)
                    ->update(['is_current' => false]);
            }

            return redirect()
                ->route('semesters.show', $semester->id)
                ->with('success', '学期更新成功');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => '更新失败: '.$e->getMessage()]);
        }
    }

    public function destroy(Semester $semester): RedirectResponse
    {
        $this->authorize('destroy', $semester);

        try {
            $semester->delete();

            return redirect()
                ->route('semesters.index')
                ->with('success', '学期删除成功');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => '删除失败: '.$e->getMessage()]);
        }
    }
}