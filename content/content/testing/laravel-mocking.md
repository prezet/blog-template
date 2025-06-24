---
title: Mastering Mocking in Laravel
date: 2025-01-10
category: Testing
excerpt: Learn how to effectively use mocks and fakes in Laravel testing to isolate dependencies and create reliable tests.
image: /prezet/img/ogimages/laravel-mocking.webp
author: jane
tags: [mocking, phpunit]
---

Laravel provides powerful mocking capabilities that allow you to isolate the code you're testing from its dependencies. Mocking is essential for creating fast, reliable tests that don't depend on external services or complex object interactions.

![Example image](writing-tests-in-laravel.webp)

## Understanding Mocking

### Test Doubles Overview

*   **Mocks:** Test doubles that simulate real objects, allowing you to control responses and verify interactions
*   **Fakes:** Laravel's built-in implementations without side effects (like file storage or mail sending)
*   **Spies:** Objects that record how they were called, useful for verifying method calls

### When to Use Mocking

Use mocking when you need to test code that depends on external services, slow operations, or when you want to verify specific method calls without executing the actual implementation.

## Laravel Facades Mocking

### Basic Facade Mocking

Laravel's facades can be easily mocked using the built-in `Mock` facade:

```php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class UserServiceTest extends TestCase
{
    public function test_user_creation_caches_data(): void
    {
        // Mock the Cache facade
        Cache::shouldReceive('put')
            ->once()
            ->with('user_count', 1, 3600);

        Cache::shouldReceive('get')
            ->with('user_count', 0)
            ->andReturn(0);

        // Your service code that uses Cache::put() will now use the mock
        $this->post('/users', ['name' => 'John', 'email' => 'john@example.com']);
    }
}
```

### Facade Assertions

Laravel's facade mocks support various assertions to verify interactions occurred as expected. You can use `shouldReceive()`, `once()`, `with()`, and `andReturn()` to control mock behavior.

## Model Mocking

### Mocking Model Methods

When testing code that interacts with Eloquent models, you can mock model methods to control database interactions:

```php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use Mockery;

class UserServiceTest extends TestCase
{
    public function test_active_users_are_retrieved(): void
    {
        // Create a mock of the User model
        $userMock = Mockery::mock('alias:' . User::class);
        
        $userMock->shouldReceive('where')
            ->with('status', 'active')
            ->andReturnSelf();
            
        $userMock->shouldReceive('get')
            ->andReturn(collect([
                (object) ['id' => 1, 'name' => 'John'],
                (object) ['id' => 2, 'name' => 'Jane']
            ]));

        $service = new UserService();
        $activeUsers = $service->getActiveUsers();

        $this->assertCount(2, $activeUsers);
    }
}
```

### Dependency Injection Mocking

You can mock service dependencies to test business logic in isolation:

```php
// Mock external dependencies
$notificationService = Mockery::mock(NotificationService::class);
$notificationService->shouldReceive('sendWelcome')
    ->once()
    ->with(Mockery::type(User::class));

$service = new UserService($notificationService);
$user = $service->createUser(['name' => 'Bob', 'email' => 'bob@example.com']);
```

## External Service Mocking

### HTTP Client Mocking

Laravel's HTTP client provides convenient faking capabilities for external APIs:

```php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\PaymentService;

class PaymentServiceTest extends TestCase
{
    public function test_successful_payment_processing(): void
    {
        // Fake HTTP responses
        Http::fake([
            'api.stripe.com/*' => Http::response([
                'id' => 'ch_12345',
                'status' => 'succeeded',
                'amount' => 2000
            ], 200),
        ]);

        $paymentService = new PaymentService();
        $result = $paymentService->processPayment(20.00, 'tok_visa');

        $this->assertTrue($result['success']);
        $this->assertEquals('ch_12345', $result['transaction_id']);

        // Verify the HTTP request was made
        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.stripe.com/v1/charges' &&
                   $request['amount'] === 2000;
        });
    }
}
```

### API Error Handling

You can also mock failed API responses to test error handling:

```php
Http::fake([
    'api.stripe.com/*' => Http::response(['error' => ['message' => 'Card declined']], 402),
]);

$result = $paymentService->processPayment(20.00, 'tok_chargeDeclined');
$this->assertFalse($result['success']);
```

## Best Practices

### Mock Strategy Guidelines

When working with mocks in Laravel, follow these guidelines:

*   **Mock external dependencies:** Focus on mocking services, APIs, and resources outside your control
*   **Use fakes when available:** Laravel's built-in fakes are often preferable to manual mocking
*   **Verify interactions:** Use assertions to ensure mocked methods are called as expected
*   **Keep mocks simple:** Avoid overly complex mock setups that are hard to maintain

### When to Use Mocking vs Fakes

*   **Use mocks for:** External APIs, complex business logic dependencies, third-party services
*   **Use fakes for:** Laravel services like Mail, Storage, Queue, Events, and HTTP client
*   **Avoid mocking:** Simple value objects, basic Laravel models, or code you want to test directly

## Running Mocked Tests

Run your tests with mocks the same way as any other test:

```bash
php artisan test
```

Mocking allows you to create fast, isolated tests that verify your application's logic without depending on external factors. This leads to more reliable test suites and faster feedback during development. 
