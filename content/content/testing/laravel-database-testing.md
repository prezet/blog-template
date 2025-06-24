---
title: Database Testing in Laravel
date: 2025-01-10
category: Testing
excerpt: Master database testing with model factories, transactions, and seeding strategies for reliable Laravel tests.
image: /prezet/img/ogimages/laravel-database-testing.webp
author: jane
tags: [database, factories, phpunit]
---

Database testing is a crucial aspect of Laravel application testing. Laravel provides powerful tools to manage test databases, create test data efficiently, and ensure your tests run in isolation.

![Example image](writing-tests-in-laravel.webp)

## Database Testing Strategies

Laravel offers several approaches for database testing:

*   **RefreshDatabase:** Migrates and rolls back the database for each test, ensuring clean state
*   **DatabaseTransactions:** Wraps each test in a transaction that's rolled back after completion
*   **Model Factories:** Generate realistic test data quickly and consistently
*   **Seeders:** Populate the database with predefined data sets for testing scenarios

## Model Factories

### Creating Factories

Model factories provide a convenient way to generate test data for your Eloquent models:

```php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'permissions' => ['manage_users', 'manage_content'],
        ]);
    }
}
```

### Factory Usage Examples

Basic factory usage in tests:

```php
// Create a single user
$user = User::factory()->create(['email' => 'john@example.com']);

// Create multiple users with relationships
$users = User::factory()
    ->count(3)
    ->has(Post::factory()->count(2))
    ->create();

// Use factory states
$admin = User::factory()->admin()->create();
$unverifiedUser = User::factory()->unverified()->create();
```

## RefreshDatabase Trait

The `RefreshDatabase` trait ensures your database is migrated and reset for each test:

```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
        
        $this->assertDatabaseCount('users', 1);
    }
}
```

You can also run seeders before tests:

```php
protected function setUp(): void
{
    parent::setUp();
    $this->seed(RoleSeeder::class); // Run specific seeder
}
```

## DatabaseTransactions Trait

The `DatabaseTransactions` trait wraps each test in a transaction that's rolled back after completion:

```php
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    public function test_order_calculation(): void
    {
        $order = Order::factory()->create(['subtotal' => 100.00]);
        $total = $order->calculateTotal();
        $this->assertEquals(108.00, $total);
    }
}
```

## Database Assertions

Laravel provides helpful assertions to verify your data operations:

```php
// Assert record exists
$this->assertDatabaseHas('users', ['email' => 'test@example.com']);

// Assert record count
$this->assertDatabaseCount('users', 1);

// Assert record missing
$this->assertDatabaseMissing('posts', ['id' => $post->id]);

// Assert model exists/missing
$this->assertModelExists($user);
$this->assertModelMissing($deletedUser);

// Soft delete assertions
$this->assertSoftDeleted('posts', ['id' => $post->id]);
```

## Best Practices

### Performance Tips

*   **Use RefreshDatabase for integration tests:** When you need complete isolation and clean state
*   **Use DatabaseTransactions for unit tests:** When testing business logic without migrations
*   **Minimize database hits:** Use `make()` instead of `create()` when you don't need persistence
*   **Use factory sequences:** For creating bulk data efficiently

### Strategy Selection

*   **RefreshDatabase:** Best for feature tests and tests requiring clean migrations
*   **DatabaseTransactions:** Best for unit tests and performance-sensitive test suites
*   **In-memory databases:** Consider SQLite in-memory for faster test execution

Database testing in Laravel provides the foundation for reliable test suites. By combining factories, appropriate database strategies, and comprehensive assertions, you can ensure your application's data layer works correctly. 
