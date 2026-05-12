<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('admin and superadmin can view customers list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create([
        'role' => 'customer',
        'name' => 'Customer A',
        'email' => 'customer-a@example.com',
        'phone' => '0909123456',
        'email_verified_at' => now(),
    ]);

    actingAs($admin)
        ->get(route('admin.customers.index'))
        ->assertOk()
        ->assertSee($customer->name)
        ->assertSee($customer->email)
        ->assertSee('0909123456')
        ->assertSee('Đã xác thực');

    $superadmin = User::factory()->create(['role' => 'superadmin']);

    actingAs($superadmin)
        ->get(route('admin.customers.index'))
        ->assertOk();
});

test('customers list only includes customer role users', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create([
        'role' => 'customer',
        'email' => 'visible-customer@example.com',
    ]);
    User::factory()->create([
        'role' => 'admin',
        'email' => 'hidden-admin@example.com',
    ]);

    actingAs($admin)
        ->get(route('admin.customers.index'))
        ->assertOk()
        ->assertSee('visible-customer@example.com')
        ->assertDontSee('hidden-admin@example.com');
});

test('admin users screen remains superadmin only and excludes customers', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email' => 'admin-user@example.com',
    ]);
    $customer = User::factory()->create([
        'role' => 'customer',
        'email' => 'customer-user@example.com',
    ]);

    actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertForbidden();

    $superadmin = User::factory()->create(['role' => 'superadmin']);

    actingAs($superadmin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('admin-user@example.com')
        ->assertDontSee('customer-user@example.com');
});
