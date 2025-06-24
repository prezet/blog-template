---
title: Understanding Laravel Routing
date: 2024-01-20
excerpt: Explore how Laravel handles incoming requests using its flexible routing system.
image: /prezet/img/ogimages/laravel-routing.webp
author: jane
tags: [routing, http, controllers]
---

Routing in Laravel defines how your application responds to different HTTP requests and URLs. You define routes in your `routes/web.php` or `routes/api.php` files.

## Basic Routing

The most basic routes accept a URI and a closure or controller action.

```php
use App\Http\Controllers\UserController;

// Basic GET Route with Closure
Route::get('/greeting', function () {
    return 'Hello World';
});

// Route redirecting to a Controller action
Route::get('/user/{id}', [UserController::class, 'show']);

// Other HTTP Verbs
Route::post('/users', [UserController::class, 'store']);
Route::put('/user/{id}', [UserController::class, 'update']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);
```

Laravel's router allows you to map URIs to closures or controller actions easily.

## Advanced Routing Features

Laravel provides several features to organize and manage your routes effectively.

### Route Parameters

You can capture segments of the URI within your route definition.

```php
Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    // Access $postId and $commentId
});
```

### Route Groups

Group routes that share attributes like middleware or prefixes.

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { ... });
    Route::get('/profile', function () { ... });
});
```

### Resource Controllers

Quickly create CRUD routes for a controller.

```php
Route::resource('photos', PhotoController::class);
```

These features help build complex and well-structured web applications.
