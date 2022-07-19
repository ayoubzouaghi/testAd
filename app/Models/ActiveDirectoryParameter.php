<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ActiveDirectoryParameter extends Model
{
    public $timestamps = true;
    protected $table = 'active_directory_parameters';

    protected $fillable = [
        'id',
        'hosts',
        'port',
        'username',
        'password',
        'dc',
        'company_id',
        'use_ssl'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'use_ssl' => 'bool',
    ];
}
