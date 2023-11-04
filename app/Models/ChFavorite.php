<?php

namespace App\Models;

use Chatify\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperChFavorite
 */
class ChFavorite extends Model
{
    use UUID;
}
