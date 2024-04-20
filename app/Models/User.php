<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $name
 * @property string $second_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $login
 * @property string $password
 * @property int $group_id
 * @property int $user_type_id
 * @property UserType $type
 * @property Group $group
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    public $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'second_name',
        'last_name',
        'email',
        'phone',
        'login',
        'password',
        'group_id',
        'user_type_id',
    ];

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
        'password' => 'hashed',
    ];


    public function isAdmin() : bool
    {
        return $this->type?->code === 'admin';
    }

    public function getExercises() : ?Collection
    {
        $group = $this->group;
        if ($group === null){
            return null;
        }
        $courses = $group->courses;
        if ($courses === null){
            return null;
        }
        $exercises = new Collection();
        foreach ($courses as $course){
            $exercises->push($course->exersizes);
        }
        return $exercises;
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(UserType::class, 'user_type_id', 'id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
