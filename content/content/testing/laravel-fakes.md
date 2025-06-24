---
title: Laravel's Built-in Fakes for Testing
date: 2025-01-10
category: Testing
excerpt: Discover how to use Laravel's powerful fake implementations to test external services without side effects.
image: /prezet/img/ogimages/laravel-fakes.webp
author: bob
tags: [fakes, phpunit]
---

Laravel provides built-in "fakes" that allow you to test interactions with external services without side effects. These fakes capture interactions for later assertions, making your tests faster and more reliable.

![Example image](writing-tests-in-laravel.webp)

## Understanding Fakes

### Fake Types Overview

*   **Fakes:** Working implementations without side effects (like sending emails or storing files)
*   **Mocks:** Manually configured test doubles that return specific responses
*   **Stubs:** Simple implementations that return predefined responses

### Benefits of Using Fakes

Laravel's fakes provide realistic behavior while capturing all interactions for verification, striking the perfect balance between isolation and functionality.

## Mail Fake

### Basic Mail Testing

The Mail fake prevents emails from being sent while recording all mail interactions:

```php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\WelcomeEmail;

class EmailTest extends TestCase
{
    public function test_order_confirmation_email_is_sent(): void
    {
        Mail::fake();

        // Perform order creation that sends email
        $response = $this->post('/orders', [
            'product_id' => 1,
            'quantity' => 2,
            'email' => 'customer@example.com'
        ]);

        // Assert the mail was sent
        Mail::assertSent(OrderConfirmation::class, function ($mail) {
            return $mail->hasTo('customer@example.com');
        });
    }
}
```

### Mail Assertions

Laravel provides several mail assertions:

```php
Mail::assertSent(WelcomeEmail::class, 2); // Assert count
Mail::assertNothingSent(); // Assert no emails sent
Mail::assertSent(WelcomeEmail::class, function ($mail) {
    return $mail->hasTo('user@example.com');
});
```

## Storage Fake

### File Upload Testing

The Storage fake provides a local filesystem that mimics your storage disk:

```php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileUploadTest extends TestCase
{
    public function test_profile_image_upload(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg', 300, 300);

        $response = $this->post('/profile/avatar', [
            'avatar' => $file
        ]);

        $response->assertRedirect('/profile');

        Storage::disk('public')->assertExists('avatars/avatar.jpg');
    }
}
```

### Storage Assertions

Common storage assertions for testing file operations:

```php
Storage::disk('public')->assertExists('file.txt');    // File exists
Storage::disk('public')->assertMissing('file.txt');   // File missing
Storage::disk('public')->put('test.txt', 'content'); // Create fake file
$files = Storage::disk('public')->files('uploads');  // List files
```

## Queue Fake

### Job Testing

The Queue fake prevents jobs from being dispatched while recording them:

```php
Queue::fake();

$this->post('/payments', ['amount' => 100, 'card_token' => 'tok_visa']);

Queue::assertPushed(ProcessPayment::class, function ($job) {
    return $job->amount === 100;
});
```

### Queue Assertions

Common queue assertions for testing job dispatch:

```php
Queue::assertPushed(JobClass::class);              // Job was pushed
Queue::assertNotPushed(JobClass::class);           // Job was not pushed
Queue::assertPushedOn('queue-name', JobClass::class); // Specific queue
Queue::assertPushed(JobClass::class, 3);           // Count assertion
```

## Other Fakes

### Event Fake

The Event fake prevents events from firing while recording them:

```php
Event::fake();
$this->post('/register', ['name' => 'John', 'email' => 'john@example.com']);
Event::assertDispatched(UserRegistered::class);
```

### HTTP Fake

Mock external API responses without making real HTTP requests:

```php
Http::fake([
    'api.example.com/*' => Http::response(['data' => 'response'], 200),
]);

$result = $this->get('/api-call');
Http::assertSent(function ($request) {
    return str_contains($request->url(), 'example.com');
});
```

## Best Practices

### Usage Guidelines

When working with Laravel's fakes, follow these guidelines:

*   **Always fake before the action:** Call the fake method before performing the operation
*   **Use specific assertions:** Laravel provides detailed assertion methods for each fake type
*   **Reset fakes between tests:** Each test should start with a clean fake state
*   **Test realistic scenarios:** Use fakes to test both success and failure cases

### Combining Multiple Fakes

You can use multiple fakes in a single test to isolate all external dependencies:

```php
Mail::fake();
Queue::fake();
Event::fake();
Storage::fake('public');

$this->post('/register', ['name' => 'Jane', 'email' => 'jane@example.com']);

Event::assertDispatched(UserRegistered::class);
Mail::assertSent(WelcomeEmail::class);
Queue::assertPushed(ProcessAvatar::class);
```

Laravel's built-in fakes provide a powerful way to test external integrations without complexity. They offer the perfect balance of realistic behavior and test isolation. 
