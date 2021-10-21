<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pin'
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function($model)
        {
            if (!$model->is_active) {
                $model->tokens()->delete();
            }
        });

        static::deleting(function($model)
        {
            $model->deleteImage();
            $model->tokens()->delete();
        });
    }

    const IMAGE_FOLDER = 'images/users/';

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists($this->getImagePath())) {
            return asset('storage/' . self::IMAGE_FOLDER . $this->image);
        };

        return null;
    }

    public function getImagePath()
    {
        return 'storage/' . self::IMAGE_FOLDER . $this->image;
    }

    public function deleteImage()
    {
        return Storage::delete("public/" . self::IMAGE_FOLDER . $this->image);
    }

    public function getIsAdminAttribute()
    {
        return $this->id == 1;
    }

    public function wrongPinAttempt()
    {
        return $this->hasOne(WrongPinAttempt::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
    
    public function attemptLogin($request)
    {
        $isLoginUser = currentApiRouteName('login-user');
        if ($isLoginUser) {
            $request = $request->only(['phone', 'phone_code', 'pin']);
            $request['is_active'] = $this->is_active;
            $validate = [
                        'phone' => ['required', function ($attr, $value, $message) use ($request) {
                            if ($this->phone !== $value && $this->phone_code !== $request['phone_code']) {
                                return $message(trans('auth.failed'));
                            }
                        }],
                        'pin' => ['required', function ($attr, $value, $message) {
                            if (!Hash::check($value, $this->pin)) {
                                return $message(trans('auth.failed'));
                            }
                        }],
                        'is_active' => [function ($attr, $value, $message) {
                            if ($value === 0) {
                                return $message(trans('auth.deactive'));
                            }
                        }]
                    ];
        } else {
            $request = $request->only(['credential', 'password', 'is_active']);
            $request['is_active'] = $this->is_active;
            $validate = [
                        'credential' => ['required', function ($attr, $value, $message) {
                            if ($this->email !== $value) {
                                return $message(trans('auth.failed'));
                            }
                        }],
                        'password' => ['required', function ($attr, $value, $message) {
                            if (!Hash::check($value, $this->password)) {
                                return $message(trans('auth.failed'));
                            }
                        }],
                        'is_active' => [function ($attr, $value, $message) {
                            if ($value === 0) {
                                return $message(trans('auth.deactive'));
                            }
                        }]
                    ];
        }

        Validator::make($request, $validate)->validate();

        return $this->requestAccessToken();
    }

    public function requestAccessToken()
    {
        return $this->withAccessToken($this->createToken('API Token')->accessToken);
    }
}
