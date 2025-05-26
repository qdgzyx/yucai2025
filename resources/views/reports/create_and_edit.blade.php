@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-10">
      @if($currentBanji)
        <div class="card shadow-lg">
          <div class="card-header ">
            <h4 class="mb-0">
              @isset($report->id)
                {{ $report->cla->grade->department->name }} - {{ $report->cla->name }} 考勤修正
              @else
                新建考勤报告
              @endisset
            </h4>
          </div>

          <div class="card-body">
            <form method="POST" 
                  action="{{ $report->id ? route('reports.update', $report->id) : route('reports.store') }}"
                  class="needs-validation" novalidate>
              @csrf
              @isset($report->id) @method('PUT') @endisset

              <!-- 核心字段 -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <label class="form-label required">报告日期</label>
                  <input type="date" name="date" class="form-control"
                         value="{{ old('date', $report->date ?? now()->toDateString()) }}"
                         max="{{ now()->toDateString() }}" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label required">所属班级</label>
                  <div class="form-control form-control-plaintext bg-light rounded">
                     {{ $currentBanji->name ?? '未选择班级' }}
                  </div>
                </div>
              </div>

              <!-- 人数统计 -->
              <div class="alert alert-info mb-4">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label required">应到人数</label>
                    <input type="number" name="total_expected" class="form-control"
                           value="{{ old('total_expected', $report->total_expected ?? $currentBanji->student_count) }}"
                           required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label required">实到人数</label>
                    <input type="number" name="total_actual" class="form-control"
                           onkeyup="calculateAbsent()" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">系统计算缺勤</label>
                    <input type="number" name="absent_count" class="form-control bg-light" readonly>
                  </div>
                </div>
              </div>

              <!-- 请假名单输入 -->
              <div class="row g-4 mb-4">
                <div class="col-md-6">
                  <label class="form-label">病假名单</label>
                  <div class="input-group tag-input">
                    <input type="text" class="form-control" 
                           id="sick_list_input" 
                           placeholder="输入学生姓名，用逗号分隔"
                           value="{{ old('sick_list', $report->sick_list ? implode(',', json_decode($report->sick_list)) : '') }}">
                    <input type="hidden" name="sick_list" id="sick_list">
                    <input type="hidden" name="sick_leave_count" id="sick_count">
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">事假名单</label>
                  <div class="input-group tag-input">
                    <input type="text" class="form-control"
                           id="personal_list_input"
                           placeholder="输入学生姓名，用逗号分隔"
                           value="{{ old('personal_list', $report->personal_list ? implode(',', json_decode($report->personal_list)) : '') }}">
                    <input type="hidden" name="personal_list" id="personal_list">
                    <input type="hidden" name="personal_leave_count" id="personal_count">
                  </div>
                </div>
              </div>

              <!-- 提交按钮 -->
              <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                  <i class="bi bi-save me-2"></i>上报
                </button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                  <i class="bi bi-arrow-left me-2"></i>返回
                </a>
              </div>
            </form>
          </div>
        </div>
      @else
        <div class="alert alert-warning text-center">
          <h4>提示：您尚未关联班级，请联系管理员完成班级绑定。</h4>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
// 自动计算缺勤人数
function calculateAbsent() {
  const expected = parseInt(document.querySelector('[name="total_expected"]').value) || 0
  const actual = parseInt(document.querySelector('[name="total_actual"]').value) || 0
  document.querySelector('[name="absent_count"]').value = expected - actual
}

// 处理名单输入
document.querySelectorAll('.tag-input input[type="text"]').forEach(input => {
  input.addEventListener('input', function() {
    const names = this.value.split(/[,，]/g).filter(n => n.trim())
    const hiddenField = this.parentElement.querySelector('input[type="hidden"]')
    const countField = this.parentElement.querySelector('[id$="_count"]')
    
    // 存储为JSON数组
    hiddenField.value = JSON.stringify(names)
    countField.value = names.length
  })
})

// 初始化时设置默认值
document.addEventListener('DOMContentLoaded', () => {
  ['sick', 'personal'].forEach(type => {
    const input = document.getElementById(`${type}_list_input`)
    const hidden = document.getElementById(`${type}_list`)
    if (hidden.value) {
      input.value = JSON.parse(hidden.value).join(',')
    }
  })
})
</script>
@endsection