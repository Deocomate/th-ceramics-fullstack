<?php

namespace App\View\Components\Client\Layouts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    /**
     * @var array<int, array{name: string, url: string, keywords: array<int, string>}>
     */
    public array $categories;

    public function __construct()
    {
        $this->categories = [
            [
                'name' => 'Ngói Âm Dương',
                'url' => route('client.products.ngoi-am-duong.index'),
                'keywords' => ['ngoi am duong', 'ngói âm dương', 'ngói âm dương', 'ngoi am', 'ngoi duong'],
            ],
            [
                'name' => 'Ngói Hài Văn Miếu',
                'url' => route('client.products.ngoi-hai-van-mieu.index'),
                'keywords' => ['ngoi hai van mieu', 'ngói hài văn miếu', 'ngoi hai', 'van mieu'],
            ],
            [
                'name' => 'Gạch Hoa Thông Gió',
                'url' => route('client.products.gach-hoa-thong-gio.index'),
                'keywords' => ['gach hoa thong gio', 'gạch hoa thông gió', 'gach bong gio', 'thong gio'],
            ],
            [
                'name' => 'Phụ Kiện Ngói',
                'url' => route('client.products.phu-kien-ngoi.index'),
                'keywords' => ['phu kien ngoi', 'phụ kiện ngói', 'bo noc', 'chu van'],
            ],
            [
                'name' => 'Gạch Trang Trí',
                'url' => route('client.products.gach-trang-tri.index'),
                'keywords' => ['gach trang tri', 'gạch trang trí', 'gach the', 'gach that'],
            ],
            [
                'name' => 'Lan Can Gốm Sứ',
                'url' => route('client.products.lan-can-gom-su.index'),
                'keywords' => ['lan can gom su', 'lan can gốm sứ', 'lan can gom xu'],
            ],
            [
                'name' => 'Gạch Cổ Bát Tràng',
                'url' => route('client.products.gach-co-bat-trang.index'),
                'keywords' => ['gach co bat trang', 'gạch cổ bát tràng', 'gach co', 'bat trang'],
            ],
            [
                'name' => 'Linh Vật Phong Thủy',
                'url' => route('client.products.linh-vat-phong-thuy.index'),
                'keywords' => ['linh vat phong thuy', 'linh vật phong thủy', 'nghe', 'phong thuy'],
            ],
            [
                'name' => 'Đèn Gốm Sứ',
                'url' => route('client.products.den-gom-su.index'),
                'keywords' => ['den gom su', 'đèn gốm sứ', 'den gom', 'den su'],
            ],
        ];
    }

    public function render(): View
    {
        return view('components.client.layouts.header');
    }
}
