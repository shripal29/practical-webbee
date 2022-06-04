<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
	protected $guarded = ['id'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu_items';

    protected $hidden = [];

    public function childiren()
    {
       return $this->hasMany('App\Models\MenuItem', 'parent_id','id')->with('childiren');
    }

}
