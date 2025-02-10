<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotificationCollection;

/**
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection $unreadNotifications
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
    * Relasi notifikasi berbasis database
    *
    * @return MorphMany
    */
   public function notifications(): MorphMany
   {
       return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
   }

   /**
    * Relasi untuk mendapatkan notifikasi yang belum dibaca.
    *
    * @return MorphMany
    */
   public function unreadNotifications(): MorphMany
   {
       return $this->notifications()->whereNull('read_at');
   }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'photo',
        'phone',
        'address',
        'norek',
        'bank',
        'role',
        'status',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi untuk mendapatkan agen
    public function scopeAgen($query)
    {
        return $query->where('role', 'agen')->where('status', 'active');
    }

    public function bookingLists()
    {
        return $this->hasMany(BookingList::class, 'agen_id', 'id');
    }

    public static function getpermissionGroup(){

        $permissions_groups = DB::table('permissions')
            ->select('group_name')
            ->groupBy('group_name')
            ->get();

        return $permissions_groups;

    }

    public static function getpermissionByGroupName($group_name){

        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();

        return $permissions;

    }

    public static function roleHasPermissions($role, $permissions){
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
            }
            return $hasPermission;
        }
    }

}
