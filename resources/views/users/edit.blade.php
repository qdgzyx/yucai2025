@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-8 offset-md-2">

    <div class="card">
      <div class="card-header">
        <h4>
          <i class="glyphicon glyphicon-edit"></i> 编辑个人资料
        </h4>
      </div>

      <div class="card-body">

        <form action="{{ route('users.update', $user->id) }}" method="POST"
            accept-charset="UTF-8"
            enctype="multipart/form-data">
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          @include('shared._error')

          <div class="form-group">
            <label for="name-field">用户名</label>
            <input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $user->name) }}" />
          </div>
          <div class="form-group">
            <label for="email-field">邮 箱</label>
            <input class="form-control" type="text" name="email" id="email-field" value="{{ old('email', $user->email) }}" />
          </div>
          <div class="form-group">
            <label for="introduction-field">个人简介</label>
            <textarea name="introduction" id="introduction-field" class="form-control" rows="3">{{ old('introduction', $user->introduction) }}</textarea>
          </div>

          <div class="form-group mb-4">
            <label for="" class="avatar-label">用户头像</label>
            <input type="file" name="avatar" class="form-control-file">

            @if($user->avatar)
              <br>
              <img class="thumbnail img-responsive" src="{{ $user->avatar }}" width="200" />
            @endif
          </div>

          {{-- 任教信息编辑区域 - 已优化字段命名 --}}
          <div class="form-group">
            <label>是否班主任</label>
            <select class="form-control" name="is_head_teacher" id="is-head-teacher">
              <option value="0" {{ $user->is_head_teacher == 0 ? 'selected' : '' }}>否</option>
              <option value="1" {{ $user->is_head_teacher == 1 ? 'selected' : '' }}>是</option>
            </select>
          </div>

          <div class="form-group" id="teaching-class-select" style="display: {{ $user->is_head_teacher ? 'block' : 'none' }};">
            <label>任教班级</label>
            <select class="form-control select2" name="teaching_classes[]" multiple>
              @foreach($banjis as $banji)
                <option value="{{ $banji->id }}" {{ ($user->teacherBanjiSubjects ?: collect())->contains('banji_id', $banji->id) ? 'selected' : '' }}>
                  {{ $banji->name }}
                </option>
              @endforeach
            </select>
            <small class="form-text text-muted">按住Ctrl键可多选</small>
          </div>

          <div class="form-group">
            <label>任教学科（可多选）</label>
            <select class="form-control select2" name="subjects[]" multiple>
              @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ ($user->teacherBanjiSubjects ?: collect())->contains('subject_id', $subject->id) ? 'selected' : '' }}>
                  {{ $subject->name }}
                </option>
              @endforeach
            </select>
            <small class="form-text text-muted">可同时选择多个任教班级的学科</small>
          </div>

          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">保存</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  // 已实现班级选择动态显示
  $(document).ready(function() {
    $('#is-head-teacher').change(function() {
      $('#teaching-class-select').toggle(this.value == 1);
    });

    // 确保页面加载时状态正确
    $('#teaching-class-select').toggle($('#is-head-teacher').val() == 1);
  });
</script>
@endsection
