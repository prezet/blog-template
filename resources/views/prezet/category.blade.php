<x-prezet.template>
    @seo([
        'title' => $document->frontmatter->title,
        'description' => $document->frontmatter->excerpt,
        'url' => route('prezet.show', ['slug' => $document->slug]),
        'image' => url($document->frontmatter->image),
    ])

    <x-prezet.alpine>
        <div class="mx-auto max-w-5xl space-y-8">
            <div class="">
                <li class="flex items-center dark:text-white">
                    <span>
                        {{ $document->category }}
                    </span>
                </li>

                <h1
                    class="mb-6 text-3xl !leading-snug font-bold sm:text-4xl md:mb-8 lg:text-5xl lg:!leading-tight dark:text-white"
                >
                    {{ $document->frontmatter->title }}
                </h1>
            </div>

            <div
                class="col-span-12 xl:col-span-10 xl:col-start-2 2xl:col-span-8 2xl:col-start-4"
            >
                <div
                    class="h-px w-full border-0 bg-zinc-200 dark:bg-zinc-700"
                ></div>
            </div>

            {{-- Main Content --}}
            {{-- prose-pre:-mx-8 prose-pre:rounded-none --}}
            <article
                class="prose-pre:rounded-xl prose-headings:font-display prose prose-zinc prose-a:border-b prose-a:border-dashed prose-a:border-black/30 prose-a:font-semibold prose-a:no-underline prose-a:hover:border-solid prose-img:rounded-sm dark:prose-invert max-w-none"
            >
                {!! $body !!}
            </article>

            <div class="grid gap-8 lg:grid-cols-2">
                @foreach ($docs as $post)
                    <x-prezet.article
                        :article="$post"
                        :author="config('prezet.authors.' . $post->frontmatter->author)"
                    />
                @endforeach
            </div>
        </div>
    </x-prezet.alpine>
</x-prezet.template>
