---
title: Decoupling Code with Laravel Events
date: 2022-07-19
excerpt: Learn how to use Laravel's event system for a simple observer implementation.
image: /prezet/img/ogimages/laravel-events.webp
author: bob
tags: [events, listeners, decoupling]
---

Laravel's events provide a simple observer pattern implementation, allowing you to subscribe and listen for various events that occur in your application. Events serve as a great way to decouple various aspects of your application.

## Defining Events & Listeners

First, define an Event class. This class typically holds data related to the event.

```bash
php artisan make:event OrderShipped
```

Then, create Listener classes that will handle the event when it's dispatched.

```bash
php artisan make:listener SendShipmentNotification --event=OrderShipped
```

Inside the listener's `handle` method, you implement the logic that should execute when the event occurs.

## Registering & Dispatching

Register your events and listeners in the `app/Providers/EventServiceProvider.php`. This tells Laravel which listeners should handle which events.

```php
protected $listen = [
    OrderShipped::class => [
        SendShipmentNotification::class,
    ],
];
```

Dispatch events using the `event()` helper or `Event` facade from anywhere in your application.

```php
use App\Events\OrderShipped;

event(new OrderShipped($order));
```

When `OrderShipped` is dispatched, the `handle` method of `SendShipmentNotification` will automatically be called.
