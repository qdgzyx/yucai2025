// 修改班级ID获取方式
public function create()
{
    // 通过关联获取班级ID（兼容动态属性）
    $banjiId = auth()->user()->banji_id; 
    
    // 或显式通过关联获取（更健壮）
    $banjiId = auth()->user()->banji->id ?? null;
    
}