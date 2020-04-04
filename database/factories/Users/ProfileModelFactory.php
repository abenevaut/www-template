<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory
    ->define(template\Domain\Users\Profiles\Profile::class, function (Faker\Generator $faker) {
        return [
            'user_id' => 0,
            'birth_date' => $faker->dateTimeThisDecade()->format('Y-m-d H:i:s'),
            'family_situation' => ($family_situation = $faker
                ->randomElement(\template\Domain\Users\Profiles\Profile::FAMILY_SITUATIONS)
            ),
            'maiden_name' => in_array(
                $family_situation,
                \template\Domain\Users\Profiles\Profile::FAMILY_SITUATIONS_WHIT_MAIDEN_NAME
            )
                ? $faker->text(100)
                : null,
        ];
    });
