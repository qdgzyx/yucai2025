@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h4 class="mb-0 text-dark text-center" style="font-family: 'Microsoft YaHei', sans-serif;">
                        <i class="bi bi-file-earmark-plus me-2"></i>作业发布
                    </h4>
                </div>

                <form action="{{ route('assignments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- 左侧表单区域 -->
                            <div class="col-md-8">
                                <!-- 学科选择 -->
                                <select id="subject_id" name="subject_id" required>
                                        @foreach($teacher->taughtSubjects->unique('id') as $subject)
                                      <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                           @endforeach
                                </select>

                                <!-- Simditor 编辑器容器 -->
                                <div class="mb-4">
                                    <label class="form-label text-secondary">
                                        <i class="bi bi-pencil-square me-1"></i>作业内容 <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="content" id="editor" class="form-control" rows="8">
                                        {{ old('content') }}
                                    </textarea>
                                </div>

                                <!-- 附件上传 -->
                                <div class="mb-4">
                                    <label class="form-label text-secondary">
                                        <i class="bi bi-paperclip me-1"></i>添加附件
                                    </label>
                                    <input type="file" name="attachment" 
                                           class="form-control" 
                                           accept=".pdf,.doc,.docx,.zip">
                                    <small class="text-muted">支持格式：PDF/DOC/ZIP（最大10MB）</small>
                                </div>
                            </div>

                            <!-- 右侧设置区域 -->
                            <div class="col-md-4">
                                <!-- 时间选择 -->
                                <div class="mb-4">
                                    <label class="form-label text-secondary">
                                        <i class="bi bi-clock me-1"></i>时间设置 <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">布置时间</span>
                                        <input type="datetime-local" 
                                               name="publish_at"
                                               class="form-control"
                                               value="{{ date('Y-m-d\TH:i') }}">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">截止时间</span>
                                        <input type="datetime-local" 
                                               name="deadline"
                                               class="form-control"
                                               value="{{ old('deadline') }}"
                                               required>
                                    </div>
                                </div>

                            <!-- 班级选择 -->
                            <div class="mb-3">
                                    <label>发布到班级（多选）</label>
                                    <select name="banji_ids[]" multiple class="form-select" size="6">
                                        @foreach($banjis as $banji)
                                            <option value="{{ $banji->id }}">{{ $banji->name }}（{{ $banji->grade->name }}）</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- 教师信息 -->
                                <div class="alert alert-light">
                                    <i class="bi bi-person-circle me-2"></i>
                                    发布教师：{{ auth()->user()->name }}
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 提交按钮 -->
                    <div class="card-footer bg-white border-top-0 py-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send-check me-2"></i>立即发布
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('styles')
<!-- Simditor 样式 -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
<style>
    .simditor {
        border-radius: 6px;
        border: 1px solid #dee2e6!important;
    }
    .simditor-toolbar {
        border-color: #dee2e6!important;
    }
    .simditor-body {
        min-height: 200px;
        padding: 15px!important;
    }
</style>
@endsection

@section('scripts')
<!-- Simditor 依赖 -->
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script> 
<script type="text/javascript" src="{{ asset('js/module.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/hotkeys.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/uploader.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/simditor.js') }}"></script>

<script>
    // Simditor 初始化
    $(document).ready(function() {
        new Simditor({
            textarea: $('#editor'),
            toolbar: [
                'title', 'bold', 'italic', 'underline', 
                'color', 'ol', 'ul', 'blockquote',
                'link', 'hr', 'indent', 'outdent'
            ],
            pasteImage: true,
            defaultImage: '/images/default.png'
        });
    });
</script>
@endsection
@endsection