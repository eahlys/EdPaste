<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paste extends Model
{
    protected $fillable = ['link', 'userId', 'title', 'content', 'ip', 'noSyntax', 'expiration', 'privacy', 'password', 'views', 'burnAfter'];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'userId', 'id');
    }
}