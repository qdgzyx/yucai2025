@extends('layouts.app3')

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
                                <th>班级</th>
                                <th>量化项目</th>
                                <th>分数</th>
                                <th>用户</th>
                                <th>检查时间</th>
                                <th>操作</th> <!-- 新增操作列 -->
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $groupedRecords = $quantify_records->groupBy(function($item) {
                                    return $item->quantify_item_id.'_'.$item->assessed_at->format('Y-m-d');
                                });
                            @endphp

                            @foreach ($groupedRecords as $groupKey => $records)
                                @foreach ($records as $index => $quantify_record)
                                    <tr>
                                        <td>{{ $quantify_record->id }}</td>
                                        <td>{{ $quantify_record->banji->name }}</td>
                                        <td>{{ $quantify_record->quantifyItem->name }}</td>
                                        <td>{{ $quantify_record->score }}</td>
                                        <td>{{ $quantify_record->user->name }}</td>
                                        <td>{{ $quantify_record->assessed_at }}</td>
                                        <td>
                                            @if($loop->first)
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('quantify_records.edit', $quantify_record) }}" 
                                                       class="btn btn-sm btn-primary">
                                                        编辑批次
                                                    </a>
                                                    
                                                    <form action="{{ route('quantify_records.destroyBatch') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="quantify_item_id" value="{{ $quantify_record->quantify_item_id }}">
                                                        <input type="hidden" name="assessed_at" value="{{ $quantify_record->assessed_at->format('Y-m-d') }}">
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('确定要删除该批次的所有记录吗？共{{ $records->count() }}条')">
                                                            删除批次
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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