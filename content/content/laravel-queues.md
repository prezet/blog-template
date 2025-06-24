---
title: Processing Jobs with Laravel Queues
date: 2024-05-05
excerpt: Understand how to defer time-consuming tasks using Laravel's queue system.
image: /prezet/img/ogimages/laravel-queues.webp
author: jane
tags: [queues, jobs, performance]
---

Laravel queues allow you to defer the processing of a time-consuming task, such as sending an email, until a later time. Deferring these tasks drastically speeds up web requests to your application.

## Configuration & Drivers

To get started, you configure your queue connection information in `config/queue.php`. Laravel supports various queue backends (drivers):

*   Database
*   Redis
*   Beanstalkd
*   Amazon SQS
*   Sync (for local development)

You choose the driver that best suits your application needs.

## Creating & Dispatching Jobs

Jobs are simple PHP classes that represent the task to be queued. You can generate a new job using Artisan:

```bash
php artisan make:job ProcessPodcast
```

Inside the job's `handle` method, you place the logic for the task. Dispatching a job to the queue is straightforward:

```php
use App\Jobs\ProcessPodcast;

// Dispatch the job to the default queue
ProcessPodcast::dispatch($podcast);

// Dispatch to a specific queue
ProcessPodcast::dispatch($podcast)->onQueue('podcasts');

// Delay execution
ProcessPodcast::dispatch($podcast)->delay(now()->addMinutes(10));
```

Remember to run a queue worker process (`php artisan queue:work`) to process the jobs as they are added to the queue. 
