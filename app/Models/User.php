<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
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

    public function parent()
    {
        return $this->belongsTo(User::class,'parent_id');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public static function tree()
    {
        $allUser  = User::get();
        $rootUser = $allUser->whereNull('parent_id');

        self::formatTree($rootUser, $allUser);

        return $rootUser;
    }

    private static function formatTree($users, $allUsers)
    {
        foreach ($users as $user) {
            $user->children = $allUsers->where('parent_id', $user->id)->values();

            if ($user->children->isNotEmpty()) {
                self::formatTree($user->children, $allUsers);
            }
        }
    }

    public function getLevel()
    {
        if ($this->parent_id) {
            return self::depthParentCount($this->parent_id);
        } else {
            return '1';
        }
        
    }

    private static function depthParentCount($id, $depth = 2)
    {
        $user = User::find($id);

        if ($user->parent_id != null) {
            $depth++;
    
            return self::depthParentCount($user->parent_id, $depth);
        } else {
            return $depth;
        }
    }

    public function getBonus()
    {
        $gchild       = 0;
        $gchild_bonus = 0;
        $child        = $this->children->count();
        $child_bonus  = $child*1;

        foreach($this->children as $child){
            $gchild += $child->children->count(); 
            $gchild_bonus = $gchild*0.5;
        }

        $bonus = $child_bonus + $gchild_bonus;
        return $bonus;

    }


}
