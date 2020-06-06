<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Friend extends Pivot
{

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECT = 'reject';

    public static $statuses = [
        self::STATUS_PENDING => ['label' => 'Pending'],
        self::STATUS_APPROVED => ['label' => 'Approved'],
        self::STATUS_REJECT => ['label' => 'Reject'],

    ];
    
    public $incrementing = true;
    protected $table = 'friends';

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];
}
