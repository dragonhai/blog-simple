<?php namespace App;

use Validator;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {
    
    /**
     * This is a useful switch to temporarily turn of automatic model validation.
     *
     * @var boolean
     */
    protected $autoValidate = true;
    
    protected $rules;

    /**
     * Bag message errors when validate
     */
    protected $errors;

    protected static function boot()
    {
        // This is an important call, makes sure that the model gets booted
        // properly!
        parent::boot();
        
        // You can also replace this with static::creating or static::updating
        // if you want to call specific validation functions for each case.
        static::saving(function($model)
        {
            if($model->autoValidate && $model->rules())
            {
                // If autovalidate is true, validate the model on create
                // and update.
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
}