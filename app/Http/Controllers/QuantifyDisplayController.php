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
        
        // 获取周期类型(日/周/月/学期)
        $periodType = $request->input('period', 'day');
        
        // 获取日期范围
        $dateRange = $this->getDateRange($periodType, $request);
        // 在QuantifyDisplayController的index方法中
       
        // 获取所有年级
        $grades = Grade::with(['banjis' => function($query) {
            $query->orderBy('name');
        }])->orderBy('name')->get();
        
        // 获取量化项目
        $quantifyItems = $currentSemester->quantifyItems()
            ->where('status', true)
            ->orderBy('type')
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
            'dateRange'
        ));
    }
    
    protected function getDateRange(string $periodType, Request $request): array
    {
        switch ($periodType) {
            case 'week':
                $start = Carbon::parse($request->input('week_start', Carbon::now()->startOfWeek()));
                return [$start, $start->copy()->endOfWeek()];
                
            case 'month':
                $month = $request->input('month', Carbon::now()->month);
                $year = $request->input('year', Carbon::now()->year);
                $start = Carbon::create($year, $month, 1);
                return [$start, $start->copy()->endOfMonth()];
                
            case 'semester':
                $semester = Semester::active()->first();
                return [$semester->start_date, $semester->end_date];
                
            default: // day
                // 修改：当未传入日期参数时，使用当天完整时间范围
                if (empty($request->day)) {
                    return [now()->startOfDay(), now()->endOfDay()];
                }
                $date = Carbon::parse($request->day);
                return [$date->copy()->startOfDay(), $date->copy()->endOfDay()];
        }
    }
    
    protected function getQuantifyData($grades, $quantifyItems, $dateRange)
    {
        $data = [];
        
        foreach ($grades as $grade) {
            foreach ($grade->banjis as $banji) {
                $row = ['banji' => $banji];
                $total = 0;
                
                foreach ($quantifyItems as $type => $items) {
                    foreach ($items as $item) {
                        // 获取该班级在该时间范围内的量化记录
                        $records = QuantifyRecord::where('banji_id', $banji->id)
                            ->where('quantify_item_id', $item->id)
                            ->whereBetween('assessed_at', $dateRange)
                            ->get();
                            
                        // 计算平均值或总和
                        $score = $records->avg('score') ?? 0;
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