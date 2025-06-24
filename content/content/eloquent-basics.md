---
title: Getting Started with Eloquent ORM
date: 2023-04-15
excerpt: Learn the fundamentals of Laravel's Eloquent ORM for interacting with your database.
image: /prezet/img/ogimages/laravel-eloquent.webp
author: jane
tags: [eloquent, database, models]
---

Eloquent is Laravel's powerful Object-Relational Mapper (ORM). It makes interacting with your database tables incredibly intuitive by representing tables as models.

## Basic Model Interaction

Eloquent provides simple methods for common database operations.

```php
// Example: Fetching all users
$users = App\Models\User::all();

// Example: Finding a user by ID
$user = App\Models\User::find(1);

// Example: Querying with constraints
$activeUsers = App\Models\User::where('active', 1)->get();
```

Eloquent handles the underlying SQL queries, allowing you to work with your data using expressive PHP syntax.

## Defining Relationships

One of Eloquent's most powerful features is the ability to define relationships between your models. This allows you to easily fetch related data.

```php
// Example: Defining a one-to-many relationship in User model
public function posts()
{
    return $this->hasMany(App\Models\Post::class);
}

// Fetching a user's posts
$user = App\Models\User::find(1);
$posts = $user->posts; // Accesses the relationship
```

You can define various relationship types like `hasOne`, `hasMany`, `belongsTo`, `belongsToMany`, etc. 
