<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    const TYPE_ADMIN = 'admin';

    const TYPE_TEACHER = 'teacher';

    const TYPE_STUDENT = 'student';

    const TYPE_GUARDIAN = 'guardian';

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'phone_number',
        'username',
        'address_id',
        'gender',
        'date_of_birth',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'user_roles',
            'user_id',
            'role_name'
        )->withTimestamps();
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'type', 'phone_number', 'username'])
            ->useLogName('user');
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }
}
