<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	protected $guarded = ['id'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'events';

    protected $hidden = [];

    public function workshops()
	{
	    return $this->hasMany('App\Models\Workshop', 'event_id', 'id');
	}
}
