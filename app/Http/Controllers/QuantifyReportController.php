<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Semester;
use App\Models\QuantifyItem;
use App\Models\QuantifyRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuantifyReportController extends Controller
{
    public function semesterReport(Request $request)
    {
        // 获取当前学期
        $currentSemester = Semester::active()->firstOrFail();
        
        // 获取学期所有月份
        $startDate = Carbon::parse($currentSemester->start_date);
        $endDate = Carbon::parse($currentSemester->end_date);
        
        $months = [];
        while ($startDate->lt($endDate)) {
            $months[] = [
                'name' => $startDate->format('Y年m月'),
                'value' => $startDate->format('Y-m')
            ];
            $startDate->addMonth();
        }
        
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
        $quantifyData = $this->getSemesterQuantifyData(
            $grades, 
            $quantifyItems,
            $currentSemester
        );
        
        return view('quantify.semester_report', compact(
            'currentSemester',
            'grades',
            'quantifyItems',
            'quantifyData',
            'months'
        ));
    }
    
    protected function getSemesterQuantifyData($grades, $quantifyItems, $semester)
    {
        $data = [];
        
        foreach ($grades as $grade) {
            foreach ($grade->banjis as $banji) {
                $row = ['banji' => $banji];
                $total = 0;
                
                foreach ($quantifyItems as $type => $items) {
                    foreach ($items as $item) {
                        // 获取该班级整个学期的量化记录
                        $records = QuantifyRecord::where('banji_id', $banji->id)
                            ->where('quantify_item_id', $item->id)
                            ->whereBetween('assessed_at', [$semester->start_date, $semester->end_date])
                            ->get();
                            
                        // 计算平均值
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
    
    public function apiSemesterReport(Request $request)
    {
        $gradeId = $request->input('grade_id');
        $month = $request->input('month');
        
        $currentSemester = Semester::active()->firstOrFail();
        $grades = Grade::with(['banjis' => function($query) {
            $query->orderBy('name');
        }])->where('id', $gradeId)->orderBy('name')->get();
        
        $quantifyItems = $currentSemester->quantifyItems()
            ->where('status', true)
            ->orderBy('type')
            ->get()
            ->groupBy('type');
            
        if ($month) {
            $startDate = Carbon::parse($month . '-01');
            $endDate = $startDate->copy()->endOfMonth();
            $dateRange = [$startDate, $endDate];
        } else {
            $dateRange = [$currentSemester->start_date, $currentSemester->end_date];
        }
        
        $quantifyData = $this->getQuantifyData($grades, $quantifyItems, $dateRange);
        
        return view('quantify._semester_report_data', compact(
            'grades',
            'quantifyItems',
            'quantifyData'
        ));
    }
}