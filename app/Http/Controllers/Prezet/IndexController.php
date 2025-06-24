<?php

namespace App\Http\Controllers\Prezet;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Prezet\Prezet\Data\DocumentData;
use Prezet\Prezet\Models\Document;

class IndexController
{
    public function __invoke(Request $request): View
    {
        $category = $request->input('category');
        $tag = $request->input('tag');
        $author = $request->input('author');

        $query = app(Document::class)::query()
            ->where('content_type', 'article')
            ->where('draft', false);

        if ($category) {
            $query->where('category', $category);
        }

        if ($tag) {
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('name', $tag);
            });
        }

        // Filter by author if provided
        if ($author) {
            $query->where('frontmatter->author', $author);
        }

        $currentAuthor = config('prezet.authors.'.$author);

        $docs = $query->orderBy('created_at', 'desc')->get();

        $docsData = $docs->map(fn (Document $doc) => app(DocumentData::class)::fromModel($doc));

        // Group posts by year
        $postsByYear = $docsData->groupBy(function ($post) {
            return $post->createdAt->format('Y');
        })->sortKeysDesc();

        return view('prezet.index', [
            'articles' => $docsData,
            'paginator' => $docs,
            'currentTag' => $request->query('tag'),
            'currentCategory' => $request->query('category'),
            'currentAuthor' => $currentAuthor,
            'postsByYear' => $postsByYear,
        ]);
    }
}
