@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h1>
          量化记录 /
          @if($quantify_record->id)
            编辑 #{{ $quantify_record->id }}
          @else
            新建
          @endif
        </h1>
        
      </div>

      <div class="card-body">
        <form action="{{ route('quantify_records.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label required">学期</label>
                    <select class="form-control" name="semester_id" required>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">量化项目</label>
                    <select class="form-control" name="quantify_item_id" required>
                        @foreach($quantify_items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">级部</label>
                    <select class="form-control" name="grade_id" id="grade-select" required>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="30%">班级名称</th>
                            <th width="20%" id="max-score-header">量化分数（{{ $quantifyItemScore }}分）</th>
                            <th width="50%">备注</th>
                        </tr>
                    </thead>
                    <tbody id="banji-scores">
                        @foreach($grades->first()->banjis as $banji)
                            <tr>
                                <td>{{ $banji->name }}</td>
                                <td>
                                    <input type="number" step="0.1" name="scores[{{ $banji->id }}]" 
                                           class="form-control" required
                                           max="{{ $quantifyItemScore }}"
                                           oninput="validateScore(this, {{ $quantifyItemScore }})">
                                    <div class="invalid-feedback" id="feedback-{{ $banji->id }}"></div>
                                </td>
                                <td>
                                    <input type="text" name="remarks[{{ $banji->id }}]" 
                                        class="form-control">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="well well-sm d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">保存</button>
                <a class="btn btn-light" href="{{ route('quantify_records.index') }}">返回列表</a>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script>
function validateScore(input, maxScore) {
    const feedback = document.getElementById(`feedback-${input.name.match(/\[(\d+)\]/)[1]}`);
    if (parseFloat(input.value) > maxScore) {
        input.classList.add('is-invalid');
        feedback.textContent = `分数不能超过${maxScore}`;
    } else {
        input.classList.remove('is-invalid'); 
        feedback.textContent = '';
    }
}

// 修改：量化项目变更时同时更新表头
document.querySelector('[name="quantify_item_id"]').addEventListener('change', function() {
    const itemId = this.value;
    fetch(`/api/quantify-items/${itemId}`)
        .then(response => response.json())
        .then(data => {
            // 更新表头显示
            document.getElementById('max-score-header').textContent = `量化分数（${data.score}分）`;
            // 更新所有分数输入框的最大值 
            document.querySelectorAll('[name^="scores["]').forEach(input => {
                input.max = data.score;
                input.setAttribute('oninput', `validateScore(this, ${data.score})`);
            });
        });
});

// 修改：级部变更时同步当前项目分数
document.getElementById('grade-select').addEventListener('change', function() {
    const gradeId = this.value;
    const quantifyItemId = document.querySelector('[name="quantify_item_id"]').value;
    
    // 获取当前项目的最大分值
    fetch(`/api/quantify-items/${quantifyItemId}`)
        .then(response => response.json())
        .then(data => {
            const maxScore = data.score;
            
            fetch(`/api/banjis?grade_id=${gradeId}`)
                .then(response => response.json())
                .then(banjis => {
                    const tbody = document.getElementById('banji-scores');
                    tbody.innerHTML = '';
                    
                    // 更新表头显示
                    document.getElementById('max-score-header').textContent = `量化分数（${maxScore}分）`;
                    
                    banjis.forEach(banji => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${banji.name}</td>
                            <td>
                                <input type="number" step="0.1" name="scores[${banji.id}]" 
                                       class="form-control" required
                                       max="${maxScore}"
                                       oninput="validateScore(this, ${maxScore})">
                                <div class="invalid-feedback" id="feedback-${banji.id}"></div>
                            </td>
                            <td>
                                <input type="text" name="remarks[${banji.id}]" class="form-control">
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                });
        });
});
</script>
@endsection
@endsection
