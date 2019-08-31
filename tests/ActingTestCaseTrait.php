<?php

namespace Tests;

use obsession\Domain\Users\
{
    Users\User,
    Profiles\Profile
};

trait ActingTestCaseTrait
{

    protected function getDefaultPassword()
    {
        return bcrypt('secret');
    }

    /**
     * Acting as logged in administrator user.
     *
     * @return User
     */
    protected function actingAsAdministrator()
    {
        $administrator = factory(User::class)->states(User::ROLE_ADMINISTRATOR)->create();
        factory(Profile::class)->create(['user_id' => $administrator->id]);
        $this->actingAs($administrator);

        return $administrator;
    }

    /**
     * Acting as logged in customer user.
     *
     * @return User
     */
    protected function actingAsCustomer()
    {
        $customer = factory(User::class)->states(User::ROLE_CUSTOMER)->create();
        factory(Profile::class)->create(['user_id' => $customer->id]);
        $this->actingAs($customer);

        return $customer;
    }
}