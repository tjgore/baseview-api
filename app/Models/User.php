<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Profile;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name', 'last_name', 
        'email',
        'password',
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
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get an array of the users role ids
     *
     * @return Collection
     */
    public function roleIds(): Collection
    {
        return $this->roles()->pluck('id');
    }

    public function roleNames(): Collection
    {
        return $this->roles()->pluck('name');
    }

    public function schools()
    {
        return $this->belongsToMany(School::class);
    }

    /**
     * Does the user attend the given school?
     *
     * @param integer $schoolId
     * @return boolean
     */
    public function attendsSchool(int $schoolId)
    {
        return $this->schools()
            ->where('school_id', $schoolId)
            ->exists();
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function hasRoles(array $roles)
    {
        return $this->roles()->whereIn('id', $roles)->exists();
    }

    public function isInternalAdmin()
    {
        return $this->hasRoles([Role::INTERNAL_ADMIN]);
    }

    /**
     * Add user to a school with a role
     *
     * @param integer $schoolId
     * @param integer $roleId
     * @return User
     */
    public function addToSchool(int $schoolId, int $roleId) :User
    {
        $this->roles()->attach($roleId);
        $this->schools()->attach($schoolId);
        return $this;
    }

}
