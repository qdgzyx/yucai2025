<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\QuantifyRecord;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QuantifyDisplayController extends Controller
{
    public function index(Request $request)
    {
        // 获取当前学期
        $currentSemester = Semester::active()->first();
        
        // 修改：移除过滤条件，始终获取全部年级
        $selectedGradeId = $request->input('grade_id');
        $grades = Grade::with(['banjis' => function($query) {
                $query->orderBy('id');
            }])
            ->orderByRaw("CAST(name AS UNSIGNED)")
            ->get();

        // 修改：优先使用请求中的grade_id，没有时使用第一个年级的ID
        $selectedGradeId = $selectedGradeId ?? ($grades->first()->id ?? null);
        
        // 新增：根据选中的年级ID过滤出当前年级
        $currentGrade = $grades->where('id', $selectedGradeId)->first();
        $filteredGrades = $currentGrade ? collect([$currentGrade]) : collect();

        // 获取周期类型(日/周/月/学期)
        $periodType = $request->input('period', 'day');
        
        // 获取日期范围
        $dateRange = $this->getDateRange($periodType, $request);
        
        // 获取量化项目
        $quantifyItems = $currentSemester->quantifyItems()
            ->where('status', true)
            ->orderBy('id')
            ->get()
            ->groupBy('type');
            
        // 获取量化数据
        $quantifyData = $this->getQuantifyData($grades, $quantifyItems, $dateRange);
        
        return view('quantify.display', compact(
            'currentSemester',
            'grades',
            'quantifyItems',
            'quantifyData',
            'periodType',
            'dateRange',
            'selectedGradeId',  // 新增返回参数
            'filteredGrades'    // 新增过滤后的年级数据
        ));
    }
    
    protected function getDateRange(string $periodType, Request $request): array
    {
        switch ($periodType) {
            case 'week':
                // 修复周解析格式问题
                $weekInput = $request->input('week_start');
                $start = $weekInput 
                    ? Carbon::createFromFormat('Y-\WW', $weekInput)->startOfWeek()
                    : now()->startOfWeek();
                return [$start, $start->copy()->endOfWeek()];

            case 'month':
                // 修复月份格式处理
                $monthYear = $request->input('month', now()->format('Y-m'));
                $start = Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
                return [$start, $start->copy()->endOfMonth()];

            case 'semester':
                $semester = Semester::active()->first();
                return [$semester->start_date, $semester->end_date];

            default: // day
                // 严格日期格式处理
                if (empty($request->day)) {
                    return [now()->startOfDay(), now()->endOfDay()];
                }
                $date = Carbon::createFromFormat('Y-m-d', $request->day);
                return [$date->copy()->startOfDay(), $date->copy()->endOfDay()];
        }
    }
    
    protected function getQuantifyData($grades, $quantifyItems, $dateRange)
    {
        $data = [];
        
        // 预加载所有需要的量化记录（新增优化代码）
        $banjiIds = $grades->pluck('banjis.*.id')->flatten()->unique();
        $itemIds = $quantifyItems->flatten()->pluck('id');
        
        $records = QuantifyRecord::whereIn('banji_id', $banjiIds)
            ->whereIn('quantify_item_id', $itemIds)
            ->whereBetween('assessed_at', $dateRange)
            ->get()
            ->groupBy(['banji_id', 'quantify_item_id']);  // 按班级和项目分组

        foreach ($grades as $grade) {
            foreach ($grade->banjis as $banji) {
                $row = ['banji' => $banji];
                $total = 0;
                
                foreach ($quantifyItems as $type => $items) {
                    foreach ($items as $item) {
                        // 从预加载数据中获取记录（修改后的代码）
                        $itemRecords = $records[$banji->id][$item->id] ?? collect([]);
                        $score = $itemRecords->avg('score') ?? 0;
                        
                        $row['items'][$type][$item->id] = round($score, 2);
                        $total += $score;
                    }
                }
                
                $row['total'] = round($total, 2);
                $data[$grade->id][$banji->id] = $row;
            }
            
            // 计算排名
            if (isset($data[$grade->id])) {
                $sorted = collect($data[$grade->id])->sortByDesc('total')->values();
                foreach ($sorted as $index => $item) {
                    $data[$grade->id][$item['banji']->id]['rank'] = $index + 1;
                }
            }
        }
        
        return $data;
    }
}