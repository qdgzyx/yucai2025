
<div class="card-body">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>量化项目</th>
                <th>班级</th>
                <th>分数</th>
                <th>备注</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quantify_records as $record)
                <tr class="{{ $record->is_archived ? 'table-secondary' : '' }}">
                    <td>{{ $record->id }}</td>
                    <td>{{ $record->quantifyItem->name ?? 'N/A' }}</td>
                    <td>{{ $record->banji->name ?? 'N/A' }}</td>
                    <td>{{ $record->score }}</td>
                    <td>{{ $record->remark }}</td>
                    <td>
                        <a href="{{ route('quantify_records.show', $record->id) }}" class="btn btn-sm btn-info">查看</a>
                        <a href="{{ route('quantify_records.edit', $record->id) }}" class="btn btn-sm btn-primary">编辑</a>
                        <form action="{{ route('quantify_records.destroy', $record->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('确认删除?')">删除</button>
                        </form>
                        // 添加: 归档按钮
                        @if (!$record->is_archived)
                            <form action="{{ route('quantify_records.archive', $record->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">归档</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
