<?php

namespace App;

use Validator;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    /**
     * This is a useful switch to temporarily turn of automatic model validation.
     *
     * @var boolean
     */
    protected $autoValidate = true;
    
    protected $rules = [
        'name' => 'required|min:6|max:50',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:6',
    ];

    /**
     * Bag message errors when validate
     */
    protected $errors;

    protected static function boot()
    {
        // This is an important call, makes sure that the model gets booted properly!
        parent::boot();
        
        // You can also replace this with static::creating or static::updating
        // if you want to call specific validation functions for each case.
        static::saving(function($model)
        {
            if($model->autoValidate && $model->rules())
            {
                // If autovalidate is true, validate the model on create and update.
                return $model->validate();
            }
        });
    }

    public function validate()
    {
        // make a new validator object
        $validator = Validator::make($this->toArray(), $this->rules());

        // check for failure
        if ($validator->fails())
        {
            // set errors and return false
            $this->errors = $validator->errors();
            return false;
        }

        // validation pass
        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function rules()
    {
        return $this->rules;
    }

    public function getAutoValidate()
    {
        return $this->autoValidate;
    }

    public function setAutoValidate($hasAutoValidate)
    {
        $this->autoValidate = $hasAutoValidate;
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * user has many posts
     *
     */
    public function posts()
    {
        return $this->hasMany('App\Posts', 'author_id');
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
