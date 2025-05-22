<form action="{{ route('user.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <input type="file" name="file" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">上传并导入</button>
</form>