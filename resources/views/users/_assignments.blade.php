<div class="assignment-list">
    @foreach ($assignments as $assignment)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ route('assignments.show', $assignment->id) }}">{{ $assignment->subject->name }}作业</a>
                </h5>
                <div class="card-text">
                    {!! $assignment->content !!}
                </div>
                <div class="text-muted mt-2">
                    <span>发布时间：{{ $assignment->publish_at->diffForHumans() }}</span>
                    <span class="mx-2">|</span>
                    <span>截止时间：{{ $assignment->deadline->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{ $assignments->appends(['tab' => 'assignments'])->links() }}