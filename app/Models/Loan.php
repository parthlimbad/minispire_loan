<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'duration',
        'first_paydate',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     * @author Parth L.
     */
    protected $casts = [
        'user_id' => 'integer',
        'amount' => 'double',
        'currency' => 'string',
        'duration' => 'integer',
        'first_paydate' => 'datetime',
        'status' => 'string',
    ];

    public function repayments()
    {
        return $this->hasMany('App\Models\Repayment');
    }
}
