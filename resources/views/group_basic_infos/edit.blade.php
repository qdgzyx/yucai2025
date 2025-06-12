@extends('layouts.app')

@section('content')
<div class="container">
    <h2>编辑班级小组</h2>
    <form method="POST" action="{{ route('group-basic-infos.update', $groupBasicInfo) }}">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="banji_id" class="form-label">所属班级</label>
            <select class="form-select" id="banji_id" name="banji_id" required>
                <option value="">请选择班级</option>
                @foreach($banjis as $banji)
                <option value="{{ $banji->id }}" 
                    {{ $groupBasicInfo->banji_id == $banji->id ? 'selected' : '' }}>
                    {{ $banji->name }}
                </option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="name" class="form-label">小组名称</label>
            <input type="text" class="form-control" id="name" name="name" 
                   value="{{ old('name', $groupBasicInfo->name) }}" required maxlength="50">
        </div>

        <div class="mb-3">
            <label for="leader" class="form-label">组长姓名</label>
            <input type="text" class="form-control" id="leader" name="leader" 
                   value="{{ old('leader', $groupBasicInfo->leader) }}" required maxlength="20">
        </div>

        <div class="mb-3">
            <label for="members" class="form-label">组员名单</label>
            <textarea class="form-control" id="members" name="members" rows="3" required>
                {{ old('members', $groupBasicInfo->members) }}
            </textarea>
            <small class="form-text text-muted">多个组员请用逗号分隔</small>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">更新小组信息</button>
            <a href="{{ route('group-basic-infos.index') }}" class="btn btn-secondary">取消返回</a>
        </div>
    </form>
</div>
@endsection