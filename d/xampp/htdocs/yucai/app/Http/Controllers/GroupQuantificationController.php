// 修改班级ID获取方式
public function create()
{
    // 通过关联获取班级ID（兼容动态属性）
    $banjiId = auth()->user()->banji_id; 
    
    // 或显式通过关联获取（更健壮）
    $banjiId = auth()->user()->banji->id ?? null;
    
}

public function groupDisplay(Request $request)
{
    $banjiId = auth()->user()->banji_id;

    // 获取班级内所有小组
    $groups = GroupBasicInfo::where('banji_id', $banjiId)->get();

    // 获取量化项目
    $quantifyItems = QuantifyItem::all()->groupBy('type');

    // 初始化量化数据
    $groupQuantifyData = [];
    foreach ($groups as $group) {
        $groupQuantifyData[$group->id] = [
            'total' => 0,
            'items' => [],
        ];
        foreach ($quantifyItems as $type => $typeItems) {
            foreach ($typeItems as $item) {
                $groupQuantifyData[$group->id]['items'][$type][$item->id] = 0;
            }
        }
    }

    // 获取选定日期范围
    $dateRange = [
        $request->input('start_date', now()->startOfMonth()->toDateString()),
        $request->input('end_date', now()->endOfMonth()->toDateString()),
    ];

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
        ->each(function($item, $key) use (&$rank) {
            $item['rank'] = $rank++;
            $groupQuantifyData[$key] = $item;
        });

    return view('group_quantify.display', [
        'banjiId' => $banjiId,
        'groups' => $groups,
        'quantifyItems' => $quantifyItems,
        'groupQuantifyData' => $groupQuantifyData,
    ]);
}
