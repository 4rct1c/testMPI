<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $name
 * @property string $second_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $login
 * @property string $password
 * @property int    $group_id
 * @property int    $user_type_id
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
    public function type() : ?UserType {
        if ($this->user_type_id === null) {
            return null;
        }
        return UserType::find($this->user_type_id);

    }

    public function getTypeCode() : string{
        return $this->type()?->code ?? 'guest';
    }
}
