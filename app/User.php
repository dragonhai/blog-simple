<?php namespace App;

use App\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The rules to use for the model validation.
     * Define your validation rules here.
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:6',
    ];

    
    /**
     * user has many posts
     *
     */
    public function posts()
    {
        return $this->hasMany('App\Posts','author_id');
    }
    
    /**
     * user has many comments
     *
     */
    public function comments()
    {
        return $this->hasMany('App\Comments','from_user');
    }
    
    /**
     * Check user can post
     *
     * @return bool
     */
    public function canPost()
    {
        return $this->hasRole(['author', 'admin']);
    }
    
    /**
     * Check user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check role user
     *
     * @example
     * $user = App\User::find(1);
     * $user -> hasRole('admin, author');
     * $user -> hasRole(['admin', 'author']);
     *
     * @var string|array
     * @return bool
     */
    public function hasRole($role)
    {
        // accept $role has type is string or array
        if (!is_array($role) && !is_string($role)) return false;

        // convert to collection for role
        $role = is_array($role) ? collect($role) : collect(explode(',', preg_replace('/\s/', '', $role)));
        
        // convert to array $this->roles
        $roles = explode(',', $this->role);

        return !! $role->intersect($roles)->count();
    }
}
