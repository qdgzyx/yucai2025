<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable 
{
    use HasApiTokens, HasFactory,HasRoles;
    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;
    use Notifiable {
        notify as protected laravelNotify;
    }

    public function notify($instance)
    {
        // 如果要通知的人是当前用户,且不是在验证邮箱，就不必通知了！
        if ($this->id == Auth::id()&&get_class($instance)!="Illuminate\Auth\Notifications\VerifyEmail") {
            return;
        }

        // 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
        if (method_exists($instance, 'toDatabase')) {
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }
     
    protected $fillable = [
        'name','email','password','introduction','avatar','subject_id','banji_id',];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
     public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    // 新增作业关联关系
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
     public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {

            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }
    public function setAvatarAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if ( ! Str::startsWith($path, 'http')) {

            // 拼接完整的 URL
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }
    // 在 User 模型中
    
    public function getLastActivedAtAttribute($value)
    {
          return $value ?? $this->created_at;
    }
    
    public function banji() 
    {
        return $this->belongsTo(Banji::class, 'banji_id'); 
    }
    
    public function taughtSubjects() {
        return $this->belongsToMany(Subject::class, 'teacher_banji_subject')
            ->withPivot(['banji_id']); 
    }
    
    // 新增量化记录关联关系
    public function quantifyRecords()
    {
        return $this->hasMany(QuantifyRecord::class);
    }
}
