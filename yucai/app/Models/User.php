// 在 User 模型中添加关联方法
class User extends Authenticatable
{

    /**
     * 获取用户所属的班级
     */
    public function banji()
    {
        return $this->hasOne(\App\Models\Banji::class, 'user_id');
    }
}