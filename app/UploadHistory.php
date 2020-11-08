<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class UploadHistory extends Model
{
	public $table = 'upload_history';

	public static function boot()
	{
	    parent::boot();
	    self::creating(function ($model) {
	        $model->created_by = (Auth::check()) ? Auth::id() : 0;
	    });
	}
}
