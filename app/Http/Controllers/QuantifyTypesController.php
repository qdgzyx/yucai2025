<?php

namespace App\Http\Controllers;

use App\Models\QuantifyType;
use App\Models\User;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuantifyTypeRequest;
use Illuminate\Support\Facades\Log;

class QuantifyTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $quantify_types = QuantifyType::with(['parent' => function($query) {
                $query->select('id', 'name'); // 只加载需要的字段
            }])
            ->orderBy('order')
            ->paginate(10);
        
        return view('quantify_types.index', compact('quantify_types'));
    }

    public function show(QuantifyType $quantify_type)
    {
        return view('quantify_types.show', [
            'quantify_type' => $quantify_type->load('children')
        ]);
    }

    public function create()
    {
        $this->authorize('create', QuantifyType::class);

        try {
            return view('quantify_types.create_and_edit', [
                'quantifyTypes' => cache()->remember('quantifyTypes_parent_null', 3600, function () {
                    return QuantifyType::whereNull('parent_id')->get();
                }),
                'users' => cache()->remember('users_id_name', 3600, function () {
                    return User::select('id', 'name')->get();
                }),
                'semesters' => cache()->remember('semesters_id_name', 3600, function () {
                    return Semester::select('id', 'name')->get();
                }),
                'quantify_type' => new QuantifyType()
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to load create page: ' . $e->getMessage());
            return back()->withError('Failed to load page. Please try again.');
        }
    }

    public function store(QuantifyTypeRequest $request)
    {
        try {
            $quantify_type = QuantifyType::create(
                $request->only(['name', 'description', 'is_active', 'parent_id', 'code', 'weight', 'order'])
            );

            return redirect()
                ->route('quantify_types.show', $quantify_type->id)
                ->with('success', '创建成功');
        } catch (\Exception $e) {
            Log::error('创建量化类型失败: '.$e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => '创建失败，请稍后再试']);
        }
    }

    public function edit(QuantifyType $quantify_type)
    {
        $this->authorize('update', $quantify_type);

        return view('quantify_types.create_and_edit', [
            'quantifyTypes' => QuantifyType::whereNull('parent_id')->get(),
            'users' => User::select('id', 'name')->get(),
            'semesters' => Semester::select('id', 'name')->get(),
            'quantify_type' => $quantify_type
        ]);
    }

    public function update(QuantifyTypeRequest $request, QuantifyType $quantify_type)
    {
        try {
            $quantify_type->update(
                $request->only(['name', 'description', 'is_active', 'parent_id', 'code', 'weight', 'order'])
            );

            return redirect()
                ->route('quantify_types.show', $quantify_type->id)
                ->with('success', '更新成功');
        } catch (\Exception $e) {
            Log::error('更新量化类型失败: '.$e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => '更新失败，请稍后再试']);
        }
    }

    public function destroy(QuantifyType $quantify_type)
    {
        $this->authorize('delete', $quantify_type);

        try {
            $quantify_type->delete();

            return redirect()
                ->route('quantify_types.index')
                ->with('success', '删除成功');
        } catch (\Exception $e) {
            Log::error('删除量化类型失败: '.$e->getMessage());
            return back()
                ->withErrors(['error' => '删除失败，请稍后再试']);
        }
    }
}
