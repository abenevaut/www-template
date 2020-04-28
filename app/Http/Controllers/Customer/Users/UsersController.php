<?php

namespace template\Http\Controllers\Customer\Users;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use template\Domain\Users\Profiles\Repositories\ProfilesRepositoryEloquent;
use template\Domain\Users\Users\Repositories\UsersRepositoryEloquent;
use template\Domain\Users\Users\User;
use template\Http\Request\Customer\Users\Profiles\ProfileFormRequest;
use template\Http\Request\Customer\Users\Users\ChangeEmailFormRequest;
use template\Http\Request\Customer\Users\Users\PasswordFormRequest;
use template\Infrastructure\Contracts\Controllers\ControllerAbstract;

class UsersController extends ControllerAbstract
{
    use ResetsPasswords;

    /**
     * @var UsersRepositoryEloquent
     */
    protected $r_users;


    protected $r_profiles;

    /**
     * UsersController constructor.
     *
     * @param UsersRepositoryEloquent $r_users
     */
    public function __construct(
        UsersRepositoryEloquent $r_users,
        ProfilesRepositoryEloquent $r_profiles
    ) {
        $this->r_users = $r_users;
        $this->r_profiles = $r_profiles;
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function edit(User $user)
    {
        $profile = $this->r_profiles->getUserProfile($user);
        $families_situations = $this
            ->r_profiles
            ->getFamilySituations()
            ->mapWithKeys(function ($item) {
                return [$item => trans("users.profiles.family_situation.{$item}")];
            });
        $timezones = $this->r_users->getTimezones();
        $locales = $this->r_users->getLocales();
        $civilities = $this
            ->r_users
            ->getCivilities()
            ->mapWithKeys(function ($item) {
                return [$item => trans("users.civility.{$item}")];
            });

        return view('customer.users.users.edit', compact(
            'profile',
            'families_situations',
            'timezones',
            'locales',
            'civilities',
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @param ProfileFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(User $user, ProfileFormRequest $request)
    {
        $id = $user->uniqid;
        $this->r_profiles->updateUserProfileWithRequest($request, $user);

        return redirect(route('customer.users.edit', compact('id')));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        return view('customer.users.users.dashboard');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @param PasswordFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function password(User $user, PasswordFormRequest $request)
    {
        $this->resetPassword($user, $request->get('password'));

        event(new PasswordReset($user));

        return redirect(route('customer.users.edit', ['id' => $user->uniqid]));
    }

    /**
     * Request email update.
     *
     * @param User $user
     * @param ChangeEmailFormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function changeEmail(User $user, ChangeEmailFormRequest $request)
    {
        $user->resetEmail($request->get('email'));

        return redirect(route('customer.users.edit', ['id' => $user->uniqid]))
            ->with('message-success', trans('auth.message_email_validation'));
    }
}
