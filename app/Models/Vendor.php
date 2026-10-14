<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'contact_person', 'phone', 'email', 'address', 'status'])]
class Vendor extends Model
{
    use SoftDeletes;

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function payables(): HasMany
    {
        return $this->hasMany(Payable::class);
    }
}
