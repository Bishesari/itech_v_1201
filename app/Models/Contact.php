<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [ 'mobile', 'created'];
    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
//    public function skillRequesters():BelongsToMany
//    {
//        return $this->belongsToMany(SkillRequester::class);
//    }
}
