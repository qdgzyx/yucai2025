@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">导入教师-班级-学科关联</h5>
                </div>

                <div class="card-body">
                    <!-- 添加成功提示区块 -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- 添加错误提示区块 -->
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 支持格式：XLSX/CSV，文件需包含班级、学科、教师列
                    </div>

                    <form action="{{ route('teacher-banji-subject.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="importFile" class="form-label">
                                <i class="fas fa-file-import"></i> 选择导入文件
                            </label>
                            <input type="file" 
                                   name="file" 
                                   id="importFile"
                                   class="form-control @error('file') is-invalid @enderror"
                                   accept=".xlsx,.csv"
                                   required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" 
                                    class="btn btn-success btn-lg"
                                    id="submitBtn">
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                开始导入
                            </button>
                            
                            <a href="{{ route('teacher-banji-subject.template') }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-file-download"></i> 下载模板
                            </a>

                            <a href="{{ route('teacher-banji-subjects.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> 返回列表
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.querySelector('.spinner-border').classList.remove('d-none');
    });
</script>
@endsection
@endsection