<?php

use App\Mail\ConsultationConfirmationMail;
use App\Mail\ConsultationRequestedMail;
use App\Models\ConsultationRequest;
use Illuminate\Support\Facades\Mail;

test('consultation submit saves record and queues admin mail', function () {
    Mail::fake();

    $response = $this->postJson(route('client.consultation.store'), [
        'customer_name' => 'Nguyen Van A',
        'phone' => '0909123456',
        'email' => 'khach@example.com',
        'note' => 'Can bao gia ngoi am duong',
        'product_id' => 99,
        'product_type' => 'ngoi_am_duong',
        'product_name' => 'Ngoi Am Duong Mau Xanh',
        'variant_name' => 'Xanh co',
    ]);

    $response
        ->assertSuccessful()
        ->assertJsonPath('status', 'success');

    expect(ConsultationRequest::count())->toBe(1);

    Mail::assertQueued(ConsultationRequestedMail::class);
    Mail::assertQueued(ConsultationConfirmationMail::class);
});

test('consultation without email only queues admin mail', function () {
    Mail::fake();

    $this->postJson(route('client.consultation.store'), [
        'customer_name' => 'Tran Van B',
        'phone' => '0912345678',
        'product_name' => 'Gach trang tri',
    ])->assertSuccessful();

    Mail::assertQueued(ConsultationRequestedMail::class);
    Mail::assertNotQueued(ConsultationConfirmationMail::class);
});

test('consultation submit validates required fields', function () {
    Mail::fake();

    $this->postJson(route('client.consultation.store'), [
        'customer_name' => '',
        'phone' => '',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['customer_name', 'phone']);

    Mail::assertNothingQueued();
    expect(ConsultationRequest::count())->toBe(0);
});

test('consultation snapshot persists without product reference', function () {
    Mail::fake();

    $this->postJson(route('client.consultation.store'), [
        'customer_name' => 'Le Thi C',
        'phone' => '0987654321',
        'product_id' => 999999,
        'product_name' => 'San pham da xoa',
        'variant_name' => 'Loai A',
    ])->assertSuccessful();

    $record = ConsultationRequest::first();

    expect($record?->product_name)->toBe('San pham da xoa')
        ->and($record?->variant_name)->toBe('Loai A')
        ->and($record?->product_id)->toBe(999999);
});
