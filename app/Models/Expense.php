<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public const CATEGORIES = ['مواد خام', 'رواتب', 'إيجار', 'كهرباء وماء', 'صيانة', 'تسويق', 'أخرى'];

    protected $fillable = ['spent_at', 'title', 'category', 'amount', 'notes'];

    protected $casts = ['spent_at' => 'date'];
}
