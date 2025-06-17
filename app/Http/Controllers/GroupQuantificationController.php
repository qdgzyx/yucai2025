<?php

namespace App\Http\Controllers;

use App\Models\GroupQuantification;
use App\Models\GroupBasicInfo;
use App\Models\Banji;
use App\Models\GroupQuantifyItem; // 修改：引入GroupQuantifyItem模型
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class GroupQuantificationController extends Controller
{
    public function index()
    {
        $quantifications = GroupQuantification::with('groupBasicInfo')->get();
        return view('group_quantifications.index', compact('quantifications'));
    }

    public function create()
    {
        // 通过访问器直接获取班级ID
        $banjiId = auth()->user()->banji_id;
        
        // 根据班级ID获取对应小组
        $groups = GroupBasicInfo::with('banji')
              ->where('banji_id', $banjiId)
              ->get();
              
        // 获取所有量化项目
        $quantifyItems = GroupQuantifyItem::all();
              
        return view('group_quantifications.create', compact('groups', 'quantifyItems'));
    }

    // 在store方法中添加班级验证
    public function store(Request $request)
    {
        // 通过访问器获取班级ID
        $userBanjiId = auth()->user()->banji_id;
    
        $validated = $request->validate([
            'quantification.content' => 'required',
            'quantification.time' => 'required|date',
            'quantifications.*.score' => 'required|integer',
            'quantifications.*.group_id' => [
                'required',
                // 验证小组是否属于当前用户班级
                Rule::exists('group_basic_infos','id')->where('banji_id', $userBanjiId)
            ]
        ]);

        foreach ($request->quantifications as $data) {
            GroupQuantification::create([
                'group_basic_info_id' => $data['group_id'],
                'content' => $request->quantification['content'], // 使用公共内容
                'score' => $data['score'],
                'time' => $request->quantification['time'], // 使用公共时间
                'recorder' => auth()->user()->name
            ]);
        }

        return redirect()->route('group_quantifications.index')->with('success', '量化记录已提交');
    }
    
    // 修改小组量化公示页面展示方法
    public function groupDisplay(Request $request)
    {
        // 获取所有班级列表
        $banjis = Banji::all();
        
        // 获取选中的班级ID
        $selectedBanjiId = $request->input('banji_id', $banjis->first()->id ?? null);
        
        // 获取周期类型
        $periodType = $request->input('period', 'day');
        
        // 计算日期范围
        $dateRange = []; 
        switch ($periodType) {
            case 'day':
                $dateRange = [Carbon::today(), Carbon::today()->endOfDay()];
                break;
            case 'week':
                $dateRange = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
                break;
            case 'month':
                $dateRange = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
                break;
            case 'semester':
                // 假设学期从9月1日开始到次年1月31日
                $start = Carbon::create(null, 9, 1);
                $end = Carbon::create(null, 1, 31)->addYear();
                $dateRange = [$start, $end];
                break;
            default:
                $dateRange = [Carbon::now(), Carbon::now()->endOfDay()];
        }
        
        // 修改：使用GroupQuantifyItem替换QuantifyItem
        $quantifyItems = GroupQuantifyItem::all()->groupBy('type');
        
        // 添加 filteredGrades 变量定义
        // 获取所有班级的年级信息（假设 Banji 模型有 grade 属性）
        $filteredGrades = Banji::all();
        
        // 获取选定班级的小组数据
        $groups = GroupBasicInfo::with('banji')
            ->where('banji_id', $selectedBanjiId)
            ->get();

        // 初始化小组量化数据
        $groupQuantifyData = [];
        foreach ($groups as $group) {
            // 初始化每个量化项目类型的分数数组
            $items = [];
            foreach ($quantifyItems as $type => $typeItems) {
                foreach ($typeItems as $item) {
                    $items[$type][$item->id] = 0; // 初始分数为0
                }
            }

            $groupQuantifyData[$group->id] = [
                'group' => $group,
                'items' => $items,
                'total' => 0, // 总分为0
                'rank' => null // 排名暂不设置
            ];
        }

        // 查询选定日期范围内的量化记录
        $records = GroupQuantification::whereBetween('time', $dateRange)
            ->whereIn('group_basic_info_id', $groups->pluck('id'))
            ->get();

        // 更新量化数据
        foreach ($records as $record) {
            if (isset($groupQuantifyData[$record->group_basic_info_id])) {
                $groupQuantifyData[$record->group_basic_info_id]['total'] += $record->score;

                // 匹配量化项目并更新分数
                foreach ($quantifyItems as $type => $typeItems) {
                    foreach ($typeItems as $item) {
                        if ($record->content === $item->name) {
                            $groupQuantifyData[$record->group_basic_info_id]['items'][$type][$item->id] += $record->score;
                        }
                    }
                }
            }
        }

        // 新增：计算小组排名（按总分降序）
        $rank = 1;
        $sortedGroups = collect($groupQuantifyData)
            ->sortByDesc('total')
            ->map(function ($item) use (&$rank) {
                $item['rank'] = $rank++;
                return $item;
            });

        // 将排序后的数据重新索引为数字键
        $groupQuantifyData = array_values($sortedGroups->toArray());

        return view('group_quantify.display', [
            'banjis' => $banjis,
            'selectedBanjiId' => $selectedBanjiId,
            'periodType' => $periodType,
            'dateRange' => $dateRange,
            'quantifyItems' => $quantifyItems,
            'filteredGrades' => $filteredGrades,
            'groupQuantifyData' => $groupQuantifyData, // 确保传递更新后的数据
        ]);
    }
}