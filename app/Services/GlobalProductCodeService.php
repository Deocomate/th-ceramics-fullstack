<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class GlobalProductCodeService
{
    /**
     * Danh sách các bảng và khóa chính tương ứng có chứa trường `code`
     * 
     * @var array<string, string>
     */
    protected array $tablesWithCode =[
        'ngoi_am_duong_ct'             => 'ngoi_am_duong_ct_id',
        'mau_sac_ngoi_hai_co_ct'       => 'mau_sac_ngoi_hai_co_ct_id',
        'mau_sac_ngoi_hai_van_mieu_ct' => 'mau_sac_ngoi_hai_van_mieu_ct_id',
        'gach_hoa_thong_gio_ct'        => 'gach_hoa_thong_gio_ct_id',
        'gach_trang_tri_ct'            => 'gach_trang_tri_ct_id',
        'gach_co_bat_trang_ct'         => 'gach_co_bat_trang_ct_id',
        'linh_vat_phong_thuy_ct'       => 'linh_vat_phong_thuy_ct_id',
        'phan_loai_ngoi_bo_noc_ct'     => 'phan_loai_ngoi_bo_noc_ct_id',
        'phan_loai_bo_noc_chu_van_ct'  => 'phan_loai_bo_noc_chu_van_ct_id',
    ];

    /**
     * Kiểm tra tính duy nhất của mã Code trên toàn hệ thống.
     *
     * @param string $code Mã sản phẩm cần kiểm tra.
     * @param string|null $ignoreTable Tên bảng của bản ghi hiện tại (Dùng khi Update).
     * @param int|null $ignoreId ID của bản ghi hiện tại (Dùng khi Update).
     * @return bool Trả về true nếu chưa tồn tại ở bất kỳ bảng nào.
     */
    public function isUnique(string $code, ?string $ignoreTable = null, ?int $ignoreId = null): bool
    {
        foreach ($this->tablesWithCode as $table => $primaryKey) {
            $query = DB::table($table)->where('code', $code);

            // Nếu đang trong quá trình update, bỏ qua bản ghi hiện tại
            if ($ignoreTable === $table && $ignoreId !== null) {
                $query->where($primaryKey, '!=', $ignoreId);
            }

            // Nếu phát hiện mã này đã tồn tại ở bảng này, lập tức return false
            if ($query->exists()) {
                return false;
            }
        }

        // Đã quét qua toàn bộ các bảng và không phát hiện trùng lặp
        return true; 
    }

    /**
     * (Tuỳ chọn) Tìm chính xác bảng nào đang chứa mã code này
     * Hữu ích nếu bạn muốn log hoặc trả về thông báo lỗi chi tiết hơn.
     */
    public function getTableContainingCode(string $code, ?string $ignoreTable = null, ?int $ignoreId = null): ?string
    {
        foreach ($this->tablesWithCode as $table => $primaryKey) {
            $query = DB::table($table)->where('code', $code);

            if ($ignoreTable === $table && $ignoreId !== null) {
                $query->where($primaryKey, '!=', $ignoreId);
            }

            if ($query->exists()) {
                return $table;
            }
        }

        return null;
    }
}