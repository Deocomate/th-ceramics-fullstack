<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DenVuonGomSuCt extends Model
{
    protected $table = 'den_vuon_gom_su_ct';
    protected $primaryKey = 'den_vuon_gom_su_ct_id';
    protected $fillable = ['name', 'images', 'des', 'size', 'size_image', 'size_des', 'is_delete'];

    protected function casts(): array {
        return ['images' => 'array', 'des' => 'array', 'size_des' => 'array'];
    }

    public function phanLoais(): HasMany {
        return $this->hasMany(PhanLoaiDenVuonGomSuCt::class, 'den_vuon_gom_su_ct_id', 'den_vuon_gom_su_ct_id');
    }
}