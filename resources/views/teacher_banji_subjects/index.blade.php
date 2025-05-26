@extends('layouts.app')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>教师</th>
                <th>学科</th>
                <th>班级</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($relations as $relation)
<tr>
    <td>{{ $relation->user->name }}</td>
    <td>{{ $relation->subject->name }}</td>
    <td>{{ $relation->banji->name }}</td>
    <td>
        <form action="#" method="POST">
            @csrf @method('DELETE')
            <button class="btn btn-danger">删除</button>
        </form>
    </td>
</tr>
@endforeach
        </tbody>
    </table>
    {{ $relations->links() }}
@endsection