<?php

namespace Tests\Feature\Client;

use App\Domains\Commerce\Infrastructure\Mail\ConsultationConfirmationMail;
use App\Domains\Commerce\Infrastructure\Mail\ConsultationRequestedMail;
use App\Domains\Commerce\Infrastructure\Mail\OrderCreatedMail;
use App\Domains\Commerce\Infrastructure\Mail\OrderStatusUpdatedMail;
use App\Domains\Commerce\Infrastructure\Models\ConsultationRequest;
use App\Domains\Commerce\Infrastructure\Models\Order;
use App\Domains\Commerce\Infrastructure\Models\OrderItem;
use App\Domains\Content\Infrastructure\Mail\ContactFormMail;
use App\Domains\Identity\Infrastructure\Models\User;
use App\Domains\Identity\Infrastructure\Notifications\ResetPasswordNotification;
use App\Domains\Identity\Infrastructure\Notifications\VerifyEmailQueued;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Markdown;
use Tests\TestCase;

class EmailRenderSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_created_email_renders_successfully(): void
    {
        $order = Order::factory()->create([
            'order_code' => 'THC-TEST-001',
            'customer_name' => 'Nguyễn Văn A',
            'phone' => '0987654321',
            'email' => 'nguyenvana@example.com',
            'address' => '123 Đường Bát Tràng, Gia Lâm, Hà Nội',
            'subtotal' => 500000,
            'discount' => 50000,
            'total_amount' => 450000,
            'coupon_code' => 'GIAM10',
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_name' => 'Ngói Âm Dương Tráng Men',
            'variant_name' => 'Men Xanh Đồng',
            'sku' => 'NAD-01',
            'price' => 25000,
            'quantity' => 20,
            'total' => 500000,
        ]);

        $order->load('items');

        $mailable = new OrderCreatedMail($order);
        $rendered = $mailable->render();

        $this->assertNotEmpty($rendered);
        $this->assertStringContainsString('THC-TEST-001', $rendered);
        $this->assertStringContainsString('Nguyễn Văn A', $rendered);
        $this->assertStringContainsString('Ngói Âm Dương Tráng Men', $rendered);
        $this->assertStringContainsString('GIAM10', $rendered);
    }

    public function test_order_status_updated_email_renders_successfully(): void
    {
        $order = Order::factory()->create([
            'order_code' => 'THC-TEST-002',
            'customer_name' => 'Trần Thị B',
            'status' => 'shipping',
        ]);

        $mailable = new OrderStatusUpdatedMail($order);
        $rendered = $mailable->render();

        $this->assertNotEmpty($rendered);
        $this->assertStringContainsString('THC-TEST-002', $rendered);
        $this->assertStringContainsString('Trần Thị B', $rendered);
    }

    public function test_consultation_requested_email_renders_successfully(): void
    {
        $record = ConsultationRequest::create([
            'customer_name' => 'Lê Văn C',
            'phone' => '0912345678',
            'email' => 'levanc@example.com',
            'note' => 'Tôi cần tư vấn gạch thông gió cho biệt thự',
            'status' => 'pending',
        ]);

        $mailable = new ConsultationRequestedMail($record);
        $rendered = $mailable->render();

        $this->assertNotEmpty($rendered);
        $this->assertStringContainsString('Lê Văn C', $rendered);
        $this->assertStringContainsString('0912345678', $rendered);
        $this->assertStringContainsString('Tôi cần tư vấn gạch thông gió', $rendered);
    }

    public function test_consultation_confirmation_email_renders_successfully(): void
    {
        $record = ConsultationRequest::create([
            'customer_name' => 'Phạm Thị D',
            'phone' => '0933221100',
            'email' => 'phamthid@example.com',
            'note' => 'Tư vấn ngói lợp chùa',
            'status' => 'pending',
        ]);

        $mailable = new ConsultationConfirmationMail($record);
        $rendered = $mailable->render();

        $this->assertNotEmpty($rendered);
        $this->assertStringContainsString('Cảm ơn bạn đã liên hệ Thanh Hải', $rendered);
        $this->assertStringContainsString('Tư vấn ngói lợp chùa', $rendered);
    }

    public function test_contact_form_email_renders_successfully(): void
    {
        $data = [
            'name' => 'Hoàng Văn E',
            'email' => 'hoangvane@example.com',
            'phone' => '0944556677',
            'subject' => 'Hỏi giá số lượng lớn',
            'message' => 'Xin báo giá 5000 viên ngói hài.',
        ];

        $mailable = new ContactFormMail($data);
        $rendered = $mailable->render();

        $this->assertNotEmpty($rendered);
        $this->assertStringContainsString('Hoàng Văn E', $rendered);
        $this->assertStringContainsString('hoangvane@example.com', $rendered);
        $this->assertStringContainsString('Xin báo giá 5000 viên ngói hài.', $rendered);
    }

    public function test_reset_password_notification_renders_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'name' => 'Test User',
        ]);

        $notification = new ResetPasswordNotification('test-token-12345');
        $mailMessage = $notification->toMail($user);
        $rendered = (string) $mailMessage->render();

        $this->assertNotEmpty($rendered);
        $this->assertStringContainsString('test-token-12345', $rendered);
    }

    public function test_verify_email_notification_renders_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'unverified@example.com',
            'name' => 'Unverified User',
            'email_verified_at' => null,
        ]);

        $notification = new VerifyEmailQueued;
        $mailMessage = $notification->toMail($user);
        $rendered = (string) $mailMessage->render();

        $this->assertNotEmpty($rendered);
        $this->assertStringContainsString('Unverified User', $rendered);
        $this->assertStringContainsString('Xác thực email', $rendered);
    }

    public function test_legacy_compatibility_email_views_render_without_errors(): void
    {
        $order = Order::factory()->create([
            'order_code' => 'THC-LEGACY-01',
            'customer_name' => 'Khách Hàng Legacy',
            'subtotal' => 100000,
            'total_amount' => 100000,
        ]);
        $order->load('items');

        $record = ConsultationRequest::create([
            'customer_name' => 'Khách Tư Vấn Legacy',
            'phone' => '0900000000',
            'status' => 'pending',
        ]);

        $contactData = [
            'name' => 'Khách Liên Hệ Legacy',
            'email' => 'legacy@example.com',
            'phone' => '0900000000',
            'message' => 'Nội dung liên hệ',
        ];

        $user = User::factory()->create();

        $markdown = app(Markdown::class);

        // 1. orders.created wrapper
        $view1 = (string) $markdown->render('components.emails.orders.created', ['order' => $order]);
        $this->assertNotEmpty($view1);
        $this->assertStringContainsString('THC-LEGACY-01', $view1);

        // 2. orders.status_updated wrapper
        $view2 = (string) $markdown->render('components.emails.orders.status_updated', ['order' => $order]);
        $this->assertNotEmpty($view2);
        $this->assertStringContainsString('THC-LEGACY-01', $view2);

        // 3. consultation.requested wrapper
        $view3 = (string) $markdown->render('components.emails.consultation.requested', ['record' => $record]);
        $this->assertNotEmpty($view3);
        $this->assertStringContainsString('Khách Tư Vấn Legacy', $view3);

        // 4. consultation.confirmation wrapper
        $view4 = (string) $markdown->render('components.emails.consultation.confirmation', ['record' => $record]);
        $this->assertNotEmpty($view4);
        $this->assertStringContainsString('Cảm ơn bạn đã liên hệ', $view4);

        // 5. contact.form wrapper
        $view5 = (string) $markdown->render('components.emails.contact.form', ['data' => $contactData]);
        $this->assertNotEmpty($view5);
        $this->assertStringContainsString('Khách Liên Hệ Legacy', $view5);

        // 6. auth.reset_password wrapper
        $view6 = (string) $markdown->render('components.emails.auth.reset_password', ['url' => 'http://localhost/reset']);
        $this->assertNotEmpty($view6);
        $this->assertStringContainsString('http://localhost/reset', $view6);

        // 7. auth.verify_email wrapper
        $view7 = (string) $markdown->render('components.emails.auth.verify_email', ['url' => 'http://localhost/verify', 'user' => $user]);
        $this->assertNotEmpty($view7);
        $this->assertStringContainsString('http://localhost/verify', $view7);
    }
}
