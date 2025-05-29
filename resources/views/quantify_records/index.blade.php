@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">量化记录</h4>
                    <div>
                        <a href="{{ route('quantify_records.create') }}" class="btn btn-primary">新增记录</a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>量化项目</th>
                                <th>分数</th>
                                <th>用户</th>
                                <th>创建时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quantify_records as $quantify_record)
                                <tr>
                                  <td>{{ $quantify_record->id }}</td>
                                  <td>{{ $quantify_record->quantifyItem->name }}</td>
                                  <td>{{ $quantify_record->score }}</td>
                                  <td>{{ $quantify_record->user->name }}</td>
                                  <td>{{ $quantify_record->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{-- 新增分页导航 --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $quantify_records->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection