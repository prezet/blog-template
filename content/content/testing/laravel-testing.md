---
title: Painless Testing in Laravel
date: 2025-01-10
category: Testing
excerpt: An introduction to writing unit and feature tests for your Laravel applications.
image: /prezet/img/ogimages/laravel-testing.webp
author: bob
tags: [phpunit, tdd]
---

Laravel is built with testing in mind. Support for testing with PHPUnit is included out of the box, and a `phpunit.xml` file is already set up for your application. Laravel classifies tests into two main categories: Feature tests and Unit tests.

![Example image](writing-tests-in-laravel.webp)

## Understanding Test Types

*   **Feature Tests:** Test a larger portion of your application, including how multiple objects interact, simulating real user actions like making HTTP requests.
*   **Unit Tests:** Test a very small, isolated piece of code, typically a single method, in isolation from the rest of the framework.

Choosing the right type of test depends on what you want to verify.

## Feature Tests

Feature tests examine how various components of your application interact. They are great for testing controller responses, database interactions within a request lifecycle, and overall application flow.

### Generating Feature Tests

You can generate new Feature test classes using Artisan:

```bash
php artisan make:test UserRegistrationTest
```

These tests extend `Tests\TestCase`, which boots the Laravel application for each test.

### HTTP Testing

Laravel provides a fluent API for making HTTP requests to your application and examining the responses.

```php
namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_a_basic_request(): void
    {
        $response = $this->get('/'); // Simulate a GET request

        $response->assertStatus(200); // Assert the HTTP status code
        $response->assertSee('Welcome'); // Assert response contains text
        $response->assertViewIs('welcome'); // Assert correct view was returned
    }

    public function test_posting_data(): void
    {
        $userData = ['name' => 'Sally', 'email' => 'sally@example.com'];
        $response = $this->post('/users', $userData);

        $response->assertRedirect('/users'); // Assert redirection
        $this->assertDatabaseHas('users', ['email' => 'sally@example.com']); // Assert data was saved
    }
}
```

### Available Assertions

Laravel provides many helpful assertions for testing responses, views, JSON structures, database state, events, and more. Check the [Laravel documentation](https://laravel.com/docs/http-tests#available-assertions) for a full list.

## Unit Tests

Unit tests focus on a very small, isolated portion of your code, usually a single method. They don't typically boot your Laravel application, making them faster than feature tests.

### Generating Unit Tests

Generate Unit tests with the `--unit` flag:

```bash
php artisan make:test UserModelTest --unit
```

These tests typically extend `PHPUnit\Framework\TestCase` directly.

### Example Unit Test

Unit tests are good for testing specific logic within a class.

```php
namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * Test if the user's full name is returned correctly.
     */
    public function test_user_has_full_name_attribute(): void
    {
        // Note: This example assumes a simple scenario
        // In real apps, you might mock dependencies
        $user = new User(['first_name' => 'Jane', 'last_name' => 'Doe']);

        $this->assertEquals('Jane Doe', $user->full_name); // Assuming a full_name accessor exists
    }
}
```

### When to Use Unit Tests

Use unit tests when you want to verify the internal logic of a specific class or method without involving other parts of the framework like HTTP requests or databases.

## Running Tests

You can run your entire test suite using the `test` Artisan command:

```bash
php artisan test
```

You can also run specific files or filter tests.

Writing both feature and unit tests helps ensure your application functions correctly and prevents regressions when making changes. 
