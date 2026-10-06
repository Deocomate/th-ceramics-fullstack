<?php

use App\Domains\Commerce\Application\OrderAdminService;
use App\Domains\Commerce\Application\Ports\OrderAdminRepositoryPort;
use App\Domains\Commerce\Application\Ports\OrderStatusMailPort;

test('order status update sends mail only when the persisted status changes', function () {
    $orders = Mockery::mock(OrderAdminRepositoryPort::class);
    $mail = Mockery::mock(OrderStatusMailPort::class);
    $orders->shouldReceive('updateStatus')->once()->with(12, 'shipping')->andReturn([
        'changed' => true,
        'email' => 'buyer@example.com',
    ]);
    $mail->shouldReceive('send')->once()->with(12, 'buyer@example.com');

    (new OrderAdminService($orders, $mail))->updateStatus(12, 'shipping');
});

test('order status update keeps the existing mail quiet when status is unchanged', function () {
    $orders = Mockery::mock(OrderAdminRepositoryPort::class);
    $mail = Mockery::mock(OrderStatusMailPort::class);
    $orders->shouldReceive('updateStatus')->once()->andReturn(['changed' => false, 'email' => 'buyer@example.com']);
    $mail->shouldNotReceive('send');

    (new OrderAdminService($orders, $mail))->updateStatus(12, 'shipping');
});
