<?php

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

test('contact form queues email and redirects back to contact page', function () {
    Mail::fake();

    $response = $this->from(route('client.contact'))->post(route('client.contact.submit'), [
        'name' => 'Nguyen Van A',
        'email' => 'khach@example.com',
        'phone' => '0909123456',
        'message' => 'Toi can tu van ve san pham ngoi am duong.',
    ]);

    $response
        ->assertRedirect(route('client.contact'))
        ->assertSessionHas('success');

    Mail::assertQueued(ContactFormMail::class, function (ContactFormMail $mail) {
        return $mail->data['email'] === 'khach@example.com'
            && $mail->data['phone'] === '0909123456';
    });
});

test('contact form validates required fields', function () {
    Mail::fake();

    $response = $this->from(route('client.contact'))->post(route('client.contact.submit'), [
        'name' => '',
        'email' => 'not-an-email',
        'phone' => '',
        'message' => 'Short',
    ]);

    $response
        ->assertRedirect(route('client.contact'))
        ->assertSessionHasErrors(['name', 'email', 'phone', 'message']);

    Mail::assertNothingQueued();
});
