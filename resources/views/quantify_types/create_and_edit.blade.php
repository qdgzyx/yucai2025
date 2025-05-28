@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h1>
          QuantifyType /
          @if(isset($quantify_type) && $quantify_type->id)
            Edit #{{ $quantify_type->id }}
          @else
            Create
          @endif
        </h1>
      </div>

      <div class="card-body">
        @if(isset($quantify_type) && $quantify_type->id)
          <form action="{{ route('quantify_types.update', $quantify_type->id) }}" method="POST" accept-charset="UTF-8">
          <input type="hidden" name="_method" value="PUT">
        @else
          <form action="{{ route('quantify_types.store') }}" method="POST" accept-charset="UTF-8">
        @endif

          @include('common.error')

          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          
                <div class="mb-3">
                    <label for="parent_id-field">父级类型</label>
                    <select class="form-control" name="parent_id" id="parent_id-field">
                        <option value="">请选择父级类型</option>
                        @foreach($quantifyTypes as $type)
                            @if($type->parent_id === null)
                                <option value="{{ $type->id }}" 
                                    @if(isset($quantify_type) && old('parent_id', $quantify_type->parent_id) == $type->id)
                                        selected
                                    @endif>
                                    {{ $type->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="code-field">Code</label>
                    <input class="form-control" type="text" name="code" id="code-field" 
                        value="{{ isset($quantify_type) ? old('code', $quantify_type->code) : old('code') }}" />
                </div>

                <div class="mb-3">
                    <label for="name-field">Name</label>
                    <input class="form-control" type="text" name="name" id="name-field" 
                        value="{{ isset($quantify_type) ? old('name', $quantify_type->name) : old('name') }}" />
                </div>

                <div class="mb-3">
                    <label for="weight-field">Weight</label>
                    <input class="form-control" type="text" name="weight" id="weight-field" value="{{ old('weight', $quantify_type->weight ) }}" />
                </div> 
                <div class="mb-3">
                    <label for="order-field">Order</label>
                    <input class="form-control" type="text" name="order" id="order-field" value="{{ old('order', $quantify_type->order ) }}" />
                </div> 
                <div class="mb-3">
                	<label for="requirements-field">Requirements</label>
                	<textarea name="requirements" id="requirements-field" class="form-control" rows="3">{{ old('requirements', $quantify_type->requirements ) }}</textarea>
                </div> 

                <div class="mb-3">
                    <label for="user_id-field">用户</label>
                    <select class="form-control" name="user_id" id="user_id-field">
                        <option value="">请选择用户</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $quantify_type->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-link float-xs-right" href="{{ route('quantify_types.index') }}"> <- Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
