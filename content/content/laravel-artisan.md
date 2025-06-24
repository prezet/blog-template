---
title: Exploring the Artisan Console
date: 2023-12-05
excerpt: Discover the power of Laravel's command-line interface, Artisan.
image: /prezet/img/ogimages/laravel-artisan.webp
author: jane
tags: [artisan, cli, commands]
---

Artisan is the command-line interface included with Laravel. It provides a number of helpful commands that can assist you while you build your application.

## Common Artisan Commands

Artisan offers commands for many common application tasks.

To view a list of all available Artisan commands, you may use the `list` command:

```bash
php artisan list
```

Some frequently used commands include:

*   **Migrations & Seeding:**
    *   `php artisan migrate`: Run database migrations.
    *   `php artisan migrate:rollback`: Rollback the last migration.
    *   `php artisan db:seed`: Seed the database with records.
*   **Code Generation:**
    *   `php artisan make:controller MyController`: Create a new controller.
    *   `php artisan make:model MyModel -m`: Create a model and its migration.
    *   `php artisan make:middleware MyMiddleware`: Create middleware.
*   **Maintenance:**
    *   `php artisan down`: Put the application into maintenance mode.
    *   `php artisan up`: Bring the application out of maintenance mode.
    *   `php artisan cache:clear`: Clear the application cache.

## Creating Custom Commands

Beyond the built-in commands, you can easily create your own custom Artisan commands for application-specific tasks.

Generate a new command class using `make:command`:

```bash
php artisan make:command SendWeeklyReport
```

Define the command's signature (how it's called) and description in the class. Implement the command's logic within the `handle` method.

```php
protected $signature = 'report:send-weekly';
protected $description = 'Send the weekly activity report';

public function handle()
{
    // Logic to generate and send the report
    $this->info('Weekly report sent successfully!');
    return Command::SUCCESS;
}
```

Register your command in `app/Console/Kernel.php`, and it becomes available via `php artisan`. 
