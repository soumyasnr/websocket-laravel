<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// More information: https://laravel.com/docs/master/broadcasting#authorizing-channels
//
// The user will have already been authenticated by Laravel. In the
// below callback, we can check whether the user is _authorized_ to
// subscribe to the channel.
//
// In routes/channels.php
Broadcast::channel('user.{userId}', function ($user, $userId) {
  if ($user->id === $userId) {
    return array('name' => $user->name);
  }
});

Broadcast::channel('Online', function ($user) {
  return $user;
});