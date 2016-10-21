<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paste extends Model
{
    protected $fillable = ['link', 'userId', 'title', 'content', 'ip', 'noSyntax', 'expiration', 'privacy', 'password', 'views'];
}