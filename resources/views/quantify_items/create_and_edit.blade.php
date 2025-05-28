@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h1>
          QuantifyItem /
          @if($quantify_item->id)
            Edit #{{ $quantify_item->id }}
          @else
            Create
          @endif
        </h1>
      </div>

      <div class="card-body">
        @if($quantify_item->id)
          <form action="{{ route('quantify_items.update', $quantify_item->id) }}" method="POST" accept-charset="UTF-8">
          <input type="hidden" name="_method" value="PUT">
        @else
          <form action="{{ route('quantify_items.store') }}" method="POST" accept-charset="UTF-8">
        @endif

          @include('common.error')

          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          
                <div class="mb-3">
                    <label for="semester_id-field">学期</label>
                    <select class="form-control" name="semester_id" id="semester_id-field">
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ old('semester_id', $quantify_item->semester_id) == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }}
                            </option>
                        @endforeach
                    </select>
                </div> 
                <div class="mb-3">
                	<label for="name-field">名称</label>
                	<input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $quantify_item->name ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="type-field">类型</label>
                    <select class="form-control" name="type" id="type-field">
                        @foreach($quantifyTypes as $type)
                            <option value="{{ $type->name }}" {{ old('type', $quantify_item->type) == $type->name ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div> 
                <div class="mb-3">
                    <label for="score-field">分数</label>
                    <input class="form-control" type="text" name="score" id="score-field" value="{{ old('score', $quantify_item->score ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="criteria-field">标准</label>
                    <input class="form-control" type="text" name="criteria" id="criteria-field" value="{{ old('criteria', $quantify_item->criteria ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="status-field">状态</label>
                    <select class="form-control" name="status" id="status-field">
                        <option value="1" {{ old('status', $quantify_item->status) == 1 ? 'selected' : '' }}>启用</option>
                        <option value="0" {{ old('status', $quantify_item->status) == 0 ? 'selected' : '' }}>禁用</option>
                    </select>
                </div>

          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-link float-xs-right" href="{{ route('quantify_items.index') }}"> <- Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
