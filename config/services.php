<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    \template\Infrastructure\Interfaces\Domain\Users\ProvidersTokens\ProvidersInterface::GITHUB => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_CALLBACK_URL', '/login/github/callback'),
        'url' => 'https://github.com/abenevaut/www-template',
        'changelog' => 'https://github.com/abenevaut/www-template/milestones?state=closed',
    ],

    \template\Infrastructure\Interfaces\Domain\Users\ProvidersTokens\ProvidersInterface::GOOGLE => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK_URL', '/login/google/callback'),
    ],

    'google_api' => [
        'key' => env('GOOGLE_API_KEY'),
    ],

    'google_recaptcha' => [
        'sitekey' => env('GOOGLE_RECAPTCHA_SITEKEY'),
        'serverkey' => env('GOOGLE_RECAPTCHA_SERVERKEY'),
    ],

    'google_tag_manager' => [
        'id' => env('GOOGLE_TM_ID', ''),
        'auth' => env('GOOGLE_TM_AUTH', ''),
        'env' => env('GOOGLE_TM_ENV', ''),
    ],

    \template\Infrastructure\Interfaces\Domain\Users\ProvidersTokens\ProvidersInterface::LINKEDIN => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('LINKEDIN_CALLBACK_URL', '/login/linkedin/callback'),
    ],

    \template\Infrastructure\Interfaces\Domain\Users\ProvidersTokens\ProvidersInterface::TWITTER => [
        'consumer_key' => env('TWITTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
        'access_token' => env('TWITTER_ACCESS_TOKEN'),
        'access_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
        'client_id' => env('TWITTER_CONSUMER_KEY'),
        'client_secret' => env('TWITTER_CONSUMER_SECRET'),
        'redirect' => env('TWITTER_CALLBACK_URL', '/login/twitter/callback'),
        'username' => '@abenevaut',
        'url' => 'https://twitter.com/abenevaut',
        /*
        |--------------------------------------------------------------------------
        | Site Card
        |--------------------------------------------------------------------------
        |
        | Twitter : twitter:card = summary, summary_large_image, app, player
        |
        */
        'card' => 'summary_large_image',
        'image' => '/images/og-image.png',
    ],

    'facebook' => [
        /*
        |--------------------------------------------------------------------------
        | Site type
        |--------------------------------------------------------------------------
        |
        | Facebook : og:type = https://developers.facebook.com/docs/reference/opengraph
        |
        */
        'og:type' => 'website',
        'og:image' => '/images/og-image.png',
    ],

];
