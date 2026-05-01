<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LanCanGomXu extends Model
{
    protected $table = 'lan_can_gom_xu';
    protected $primaryKey = 'lan_can_gom_xu_id';
    protected $fillable =[
        'thumbnail_main',
        'video',
    ];
}