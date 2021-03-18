<?php

namespace App\Models\Sanctum;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken  extends SanctumPersonalAccessToken
{

	 protected $table = 'personal_access_tokens';    
}
