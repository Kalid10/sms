<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'fcm_tokens',
        'openai_daily_usage',
        'profile_image',
        'is_blocked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date:Y-m-d',
        'email_verified_at' => 'datetime',
        'fcm_tokens' => 'array',
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

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function isTeacher(): bool
    {
        return $this->type === self::TYPE_TEACHER;
    }

    public function absentee(): HasMany
    {
        return $this->hasMany(Absentee::class);
    }

    public function guardian(): HasOne
    {
        return $this->hasOne(Guardian::class);
    }

    /**
     * Mutate attribute to avoid null return,
     * and always return type array
     */
    public function fcmTokens(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? json_decode($value) : []
        );
    }

    /**
     * Specifies the user's FCM tokens
     */
    public function routeNotificationForFcm(): array
    {
        return $this->fcm_tokens;
    }

    public function assessmentMapping(): HasMany
    {
        return $this->hasMany(AssessmentMapping::class);
    }
}
