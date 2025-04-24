@php
    /* @var array $nav */
    /* @var array|null|string $currentTag */
    /* @var array|null|string $currentCategory */
    /* @var \Illuminate\Support\Collection<int,\Prezet\Prezet\Data\DocumentData> $articles */
    /* @var \Illuminate\Support\Collection $postsByYear */
    /* @var \Illuminate\Support\Collection $allCategories */
    /* @var \Illuminate\Support\Collection $allTags */
@endphp

<x-prezet.template>
    @seo([
        'title' => 'Prezet: Markdown Blogging for Laravel',
        'description' =>
            'Transform your markdown files into SEO-friendly blogs, articles, and documentation!',
        'url' => route('prezet.index'),
    ])

    <div class="mx-auto max-w-4xl">
        <h1
            class="text-3xl !leading-snug font-bold sm:text-4xl lg:text-5xl lg:!leading-tight dark:text-white"
        >
            All Posts
        </h1>

        <div class="mb-6 justify-between sm:flex md:mb-8">
            <p class="text-lg leading-7 text-zinc-500 dark:text-zinc-400">
                A blog created with Laravel and Tailwind.css
            </p>
            <div class="mt-4 block sm:mt-0">
                @if ($currentTag)
                    <span
                        class="inline-flex items-center gap-x-0.5 rounded-md bg-zinc-50 px-2.5 py-1.5 text-xs font-medium text-zinc-600 ring-1 ring-zinc-500/10 ring-inset dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700/20"
                    >
                        <x-prezet.icon-tag class="mr-1 size-3.5" />

                        {{ strtoupper($currentTag) }}
                        <a
                            href="{{ route('prezet.index', array_filter(request()->except('tag'))) }}"
                            class="group relative -mr-1 h-3.5 w-3.5 rounded-xs hover:bg-zinc-500/20 dark:hover:bg-zinc-600/30"
                        >
                            <span class="sr-only">Remove</span>
                            <svg
                                viewBox="0 0 14 14"
                                class="h-3.5 w-3.5 stroke-zinc-600/50 group-hover:stroke-zinc-600/75 dark:stroke-zinc-400/50 dark:group-hover:stroke-zinc-400/75"
                            >
                                <path d="M4 4l6 6m0-6l-6 6" />
                            </svg>
                            <span class="absolute -inset-1"></span>
                        </a>
                    </span>
                @endif

                @if ($currentCategory)
                    <span
                        class="inline-flex items-center gap-x-0.5 rounded-md bg-zinc-50 px-2.5 py-1.5 text-xs font-medium text-zinc-600 ring-1 ring-zinc-500/10 ring-inset dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700/20"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="mr-1 size-3.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                            />
                        </svg>

                        {{ $currentCategory }}
                        <a
                            href="{{ route('prezet.index', array_filter(request()->except('category'))) }}"
                            class="group relative -mr-1 h-3.5 w-3.5 rounded-xs hover:bg-zinc-500/20 dark:hover:bg-zinc-600/30"
                        >
                            <span class="sr-only">Remove</span>
                            <svg
                                viewBox="0 0 14 14"
                                class="h-3.5 w-3.5 stroke-zinc-600/50 group-hover:stroke-zinc-600/75 dark:stroke-zinc-400/50 dark:group-hover:stroke-zinc-400/75"
                            >
                                <path d="M4 4l6 6m0-6l-6 6" />
                            </svg>
                            <span class="absolute -inset-1"></span>
                        </a>
                    </span>
                @endif

                @if ($currentAuthor)
                    <span
                        class="inline-flex items-center gap-x-0.5 rounded-md bg-zinc-50 px-2.5 py-1.5 text-xs font-medium text-zinc-600 ring-1 ring-zinc-500/10 ring-inset dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700/20"
                    >
                        <img
                            src="{{ $currentAuthor['image'] }}"
                            alt="{{ $currentAuthor['name'] }}"
                            class="mr-1 h-3.5 w-3.5 rounded-full"
                        />
                        {{ $currentAuthor['name'] }}
                        <a
                            href="{{ route('prezet.index', array_filter(request()->except('author'))) }}"
                            class="group relative -mr-1 h-3.5 w-3.5 rounded-xs hover:bg-zinc-500/20 dark:hover:bg-zinc-600/30"
                        >
                            <span class="sr-only">Remove</span>
                            <svg
                                viewBox="0 0 14 14"
                                class="h-3.5 w-3.5 stroke-zinc-600/50 group-hover:stroke-zinc-600/75 dark:stroke-zinc-400/50 dark:group-hover:stroke-zinc-400/75"
                            >
                                <path d="M4 4l6 6m0-6l-6 6" />
                            </svg>
                            <span class="absolute -inset-1"></span>
                        </a>
                    </span>
                @endif
            </div>
        </div>

        @foreach ($postsByYear as $year => $posts)
            <section class="mb-12">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div
                            class="w-full border-t border-zinc-200 dark:border-zinc-800"
                        ></div>
                    </div>
                    <div class="relative flex justify-start">
                        <span
                            class="bg-white pr-4 text-xl font-bold text-zinc-500 dark:bg-zinc-950 dark:text-zinc-400"
                        >
                            {{ $year }}
                        </span>
                    </div>
                </div>

                <div class="mt-8 space-y-12 text-zinc-900 dark:text-zinc-100">
                    @foreach ($posts as $post)
                        <x-prezet.article
                            :article="$post"
                            :author="config('prezet.authors.' . $post->frontmatter->author)"
                        />
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</x-prezet.template>
