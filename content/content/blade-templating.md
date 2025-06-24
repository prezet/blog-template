---
title: Introduction to Blade Templates
date: 2022-11-01
excerpt: Discover Laravel's simple yet powerful templating engine, Blade.
image: /prezet/img/ogimages/laravel-blade.webp
author: bob
tags: [blade, views, frontend]
---

Blade is Laravel's intuitive templating engine. Unlike other PHP templating engines, Blade does not restrict you from using plain PHP code in your views. In fact, all Blade views are compiled into plain PHP code and cached until they are modified.

Blade files use the `.blade.php` file extension.

## Displaying Data & Control Structures

Blade makes it easy to display variables passed from your controller and use common control structures.

```blade
<!-- Displaying Data -->
<h1>Hello, {{ $name }}</h1>

<!-- Control Structure Example -->
@if (count($records) === 1)
    I have one record!
@elseif (count($records) > 1)
    I have multiple records!
@else
    I don't have any records!
@endif

<!-- Looping -->
@foreach ($users as $user)
    <p>This is user {{ $user->id }}</p>
@endforeach
```

Blade provides convenient shortcuts for common PHP control structures, such as conditional statements and loops.

## Template Inheritance

Blade allows you to define a master layout and extend it in child views. This helps maintain consistency across your application.

```blade
<!-- layouts/app.blade.php -->
<html>
    <head>
        <title>App Name - @yield('title')</title>
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>

<!-- Child view -->
@extends('layouts.app')

@section('title', 'Page Title')

@section('content')
    <p>This is my body content.</p>
@endsection
```

The `@yield` directive specifies where content from child sections should be injected. 
