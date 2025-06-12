<?php

namespace App\Http\Controllers;

use App\Models\GroupBasicInfo;
use Illuminate\Http\Request;
use App\Imports\GroupBasicInfosImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\GroupQuantification;

class GroupBasicInfoController extends Controller
{
    public function index()
    {
        $groups = GroupBasicInfo::with('banji')->paginate(10);
        return view('group_basic_infos.index', compact('groups'));
    }

    public function create()
    {
        $banjis = \App\Models\Banji::all(); // 获取所有班级用于下拉选择
        return view('group_basic_infos.create', compact('banjis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'banji_id' => 'required|exists:banjis,id',
            'name' => 'required|max:50',
            'leader' => 'required|max:20',
            'members' => 'required'
        ]);

        GroupBasicInfo::create($validated);
        return redirect()->route('group-basic-infos.index')->with('success', '小组创建成功');
    }

    public function edit(GroupBasicInfo $groupBasicInfo)
    {
        $banjis = \App\Models\Banji::all();
        return view('group_basic_infos.edit', compact('groupBasicInfo', 'banjis'));
    }

    public function update(Request $request, GroupBasicInfo $groupBasicInfo)
    {
        $validated = $request->validate([
            'banji_id' => 'required|exists:banjis,id',
            'name' => 'required|max:50',
            'leader' => 'required|max:20',
            'members' => 'required'
        ]);

        $groupBasicInfo->update($validated);
        return redirect()->route('group-basic-infos.index')->with('success', '小组更新成功');
    }

    public function destroy(GroupBasicInfo $groupBasicInfo)
    {
        // 开启事务确保操作原子性
        DB::transaction(function () use ($groupBasicInfo) {
            // 删除关联的量化记录
            GroupQuantification::where('group_basic_info_id', $groupBasicInfo->id)->delete();
            
            // 删除小组
            $groupBasicInfo->delete();
        });
        
        return redirect()->route('group-basic-infos.index')->with('success', '小组已删除');
    }
    
    public function showForm()
    {
        return view('group_basic_infos.import');
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);
        
        Excel::import(new GroupBasicInfosImport, $request->file('file'));
        
        return back()->with('success', '小组信息导入成功！');
    }
}