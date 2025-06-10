@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">用户信息导入</h5>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 支持格式：XLSX/CSV，文件需包含完整用户信息
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('errors'))
                        <div class="alert alert-danger">
                            <ul>
                                @foreach(session('errors')->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('user.import') }}" method="POST" enctype="multipart/form-data">
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
                            
                            <a href="{{ route('user.template') }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-file-download"></i> 下载模板
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