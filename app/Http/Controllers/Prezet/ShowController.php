<?php

namespace App\Http\Controllers\Prezet;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Prezet\Prezet\Data\DocumentData;
use Prezet\Prezet\Models\Document;
use Prezet\Prezet\Prezet;

class ShowController
{
    public function __invoke(Request $request, string $slug): View
    {
        $doc = Prezet::getDocumentModelFromSlug($slug);
        $md = Prezet::getMarkdown($doc->filepath);
        $html = Prezet::parseMarkdown($md)->getContent();
        $docData = Prezet::getDocumentDataFromFile($doc->filepath);

        if ($docData->contentType === 'category') {
            $docs = app(Document::class)::query()
                ->where('content_type', 'article')
                ->where('draft', false)
                ->where('category', $doc->category)
                ->orderBy('created_at', 'desc')->get();

            $docsData = $docs->map(fn (Document $doc) => app(DocumentData::class)::fromModel($doc));

            return view('prezet.category', [
                'document' => $docData,
                'body' => $html,
                'docs' => $docsData,
            ]);
        }

        $linkedData = json_encode(Prezet::getLinkedData($docData), JSON_UNESCAPED_SLASHES);
        $headings = Prezet::getHeadings($html);
        $authorKey = $docData->frontmatter->author;
        $author = config('prezet.authors.'.$authorKey, null);

        return view('prezet.show', [
            'document' => $docData,
            'linkedData' => $linkedData,
            'headings' => $headings,
            'body' => $html,
            'author' => $author,
        ]);
    }
}
