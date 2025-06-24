---
title: Laravel Middleware Explained
date: 2023-08-11
excerpt: Learn how middleware can filter HTTP requests entering your application.
image: /prezet/img/ogimages/laravel-middleware.webp
author: bob
tags: [middleware, http, requests, security]
---

Middleware provide a convenient mechanism for inspecting and filtering HTTP requests entering your application. For example, Laravel includes middleware that verifies the user of your application is authenticated.

## Creating Middleware

Middleware classes typically reside in the `app/Http/Middleware` directory. You can generate a new middleware using the Artisan command:

```bash
php artisan make:middleware EnsureTokenIsValid
```

Inside the middleware's `handle` method, you implement your filtering logic. You can either pass the request deeper into the application or return a response directly.

```php
public function handle(Request $request, Closure $next)
{
    if ($request->input('token') !== 'my-secret-token') {
        return redirect('home');
    }

    return $next($request);
}
```

## Registering & Assigning Middleware

Before you can use middleware, you need to register it in `app/Http/Kernel.php`. Middleware can be registered globally (to run on every request), assigned to route groups, or assigned to specific routes.

```php
// Assigning middleware to a route
Route::get('/profile', function () {
    //
})->middleware(EnsureTokenIsValid::class);

// Assigning middleware to a group
Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::get('/', function () { ... });
    Route::get('/user/profile', function () { ... });
});
```

Middleware can perform tasks like authentication, logging, CORS handling, or modifying the incoming request before it reaches your route or controller. 
