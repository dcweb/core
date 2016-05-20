<?php

return array(

      /*
      |--------------------------------------------------------------------------
      | Authentication Guards
      |--------------------------------------------------------------------------
      |
      | Next, you may define every authentication guard for your application.
      | Of course, a great default configuration has been defined for you
      | here which uses session storage and the Eloquent user provider.
      |
      | All authentication drivers have a user provider. This defines how the
      | users are actually retrieved out of your database or other storage
      | mechanisms used by this application to persist your user's data.
      |
      | Supported: "session", "token"
      |
      */
      'guards' => [

          'dcms' => [
              'driver' => 'session',
              'provider' => 'dcms',
          ],
        ],


      /*
      |--------------------------------------------------------------------------
      | User Providers
      |--------------------------------------------------------------------------
      |
      | All authentication drivers have a user provider. This defines how the
      | users are actually retrieved out of your database or other storage
      | mechanisms used by this application to persist your user's data.
      |
      | If you have multiple user tables or models you may configure multiple
      | sources which represent each model / table. These sources may then
      | be assigned to any extra authentication guards you have defined.
      |
      | Supported: "database", "eloquent"
      |
      */
      'providers' => [
          'dcms' => [
              'driver' => 'eloquent',
              'model' => Dcms\Core\Models\Users\User::class,
          ],
      ],
  );
?>
