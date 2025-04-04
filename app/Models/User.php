<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;


class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;
    use HasFactory;
    use Searchable;

    protected $fillable = ['username', 'name', 'email', 'password', 'is_admin'];

    protected $searchableFields = ['*'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    // protected static function booted()
    // {
    //     static::addGlobalScope('excludeSuperAdmin', function (Builder $builder) {
    //         $builder->where('name', '!=', 'Super Admin');
    //     });
    // }

    public function goals()
    {
        return $this->hasMany(Goal::class, 'updated_by');
    }

    public function perspectives()
    {
        return $this->hasMany(Perspective::class, 'created_by_id');
    }

    public function perspectives2()
    {
        return $this->hasMany(Perspective::class, 'updated_by_id');
    }

    public function objectives()
    {
        return $this->hasMany(Objective::class, 'created_by_id');
    }

    public function objectives2()
    {
        return $this->hasMany(Objective::class, 'updated_by_id');
    }

    public function strategies()
    {
        return $this->hasMany(Strategy::class, 'created_by_id');
    }

    public function strategies2()
    {
        return $this->hasMany(Strategy::class, 'updated_by_id');
    }

    public function keyPeformanceIndicators()
    {
        return $this->hasMany(KeyPeformanceIndicator::class, 'created_by_id');
    }

    public function offices2()
    {
        return $this->hasMany(Office::class, 'holder_id');
    }


    public function offices()
    {
        return $this->belongsToMany(Office::class, 'manager');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

     public function performers()
    {
        return $this->hasMany(Performer::class, 'user_id');
    }
    public function hasPermission($check_permission){
        $user = auth()->user();
        $permissions =  $user->getPermissionsViaRoles();
        foreach ($permissions as $key => $permission) {
             if($permission->name == $check_permission){
                return true;
            }
        }
         return false;
    }

    public function assignedTasks()
    {
        return $this->hasMany(TaskAssign::class, 'assigned_to_id');
    }
}
