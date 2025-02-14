<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperInventoryItem
 */
class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];
}
