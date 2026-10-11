<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Domain\ContentPageRegistry;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerServiceController extends Controller
{
    public function show(string $page): View|RedirectResponse
    {
        if (str_ends_with($page, '.html')) {
            $cleanPage = ContentPageRegistry::stripHtmlExtension($page);

            return redirect()->route('client.customer-service.show', $cleanPage, 301);
        }

        if (ContentPageRegistry::isValidCustomerServicePage($page)) {
            $views = [
                'bao-mat-thong-tin' => 'clients.content.dich-vu-khach-hang.bao-mat-thong-tin',
                'chinh-sach-doi-tra' => 'clients.content.dich-vu-khach-hang.chinh-sach-doi-tra',
                'chinh-sach-van-chuyen' => 'clients.content.dich-vu-khach-hang.chinh-sach-van-chuyen',
                'huong-dan-thi-cong' => 'clients.content.dich-vu-khach-hang.huong-dan-thi-cong',
                'quy-trinh-dat-hang' => 'clients.content.dich-vu-khach-hang.quy-trinh-dat-hang',
                'tai-catalog' => 'clients.content.dich-vu-khach-hang.tai-catalog',
                'tai-khoan-cua-toi' => 'clients.identity.dich-vu-khach-hang.tai-khoan-cua-toi',
                'trang-thai-don-hang' => 'clients.commerce.dich-vu-khach-hang.trang-thai-don-hang',
            ];
            $viewKey = $views[$page] ?? "clients.dich-vu-khach-hang.{$page}";
            if (view()->exists($viewKey)) {
                return view($viewKey);
            }

            return view("clients.dich-vu-khach-hang.{$page}");
        }

        abort(404);
    }
}
