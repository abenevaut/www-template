<?php

namespace template\Http\Request\Customer\Users\Users;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use template\Infrastructure\Contracts\Request\RequestAbstract;
use template\Domain\Users\Users\Repositories\UsersResetPasswordRepositoryEloquent;

class PasswordFormRequest extends RequestAbstract
{

    /**
     * @var UsersResetPasswordRepositoryEloquent
     */
    protected $r_users;

    /**
     * PasswordFormRequest constructor.
     *
     * @param UsersResetPasswordRepositoryEloquent $r_users
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     */
    public function __construct(
        UsersResetPasswordRepositoryEloquent $r_users,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct(
            $query,
            $request,
            $attributes,
            $cookies,
            $files,
            $server,
            $content
        );

        $this->r_users = $r_users;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        Validator::extend(
            'validpassword',
            function ($attribute, $value, $parameters) {
                return Hash::check($value, Auth::user()->password);
            }
        );

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->r_users->getChangePasswordRules();
    }
}
