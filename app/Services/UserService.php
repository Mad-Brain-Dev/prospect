<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Str;
use App\Services\BaseService;

class UserService extends BaseService
{

    protected $model;

    public function __construct()
    {
        $this->model = User::class;
    }

    public function storeOrUpdate($data, $id = null)
    {
        try {
            // manage additional data
            $data['password'] = Hash::make($data['password']);
            // Call patent method
            return parent::storeOrUpdate($data, $id);
        } catch (\Exception $e) {
            $this->logFlashThrow($e);
        }
    }
}

