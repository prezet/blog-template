<?php

namespace Prezet\BlogTemplate\Tests\Feature\Http\Controllers;

use Prezet\BlogTemplate\Tests\TestCase;
use Prezet\Prezet\Models\Document;
use Prezet\Prezet\Models\Heading;

class SearchControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedTestData();
    }

    public function test_search_returns_expected_results(): void
    {
        $response = $this->getJson(route('prezet.search', [
            'q' => 'Laravel',
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'level' => 1,
                'documentId' => 1,
                'section' => 'Introduction to Laravel',
                'text' => 'Introduction to Laravel',
                'slug' => 'intro-to-laravel',
            ]);
    }

    public function test_search_returns_empty_results_for_non_matching_query(): void
    {
        $response = $this->getJson(route('prezet.search', [
            'q' => 'NonExistentTerm',
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function test_search_validates_query_parameter(): void
    {
        $response = $this->getJson(route('prezet.search'));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['q']);
    }

    public function test_search_excludes_draft_documents(): void
    {
        $response = $this->getJson(route('prezet.search', [
            'q' => 'Introduction to Laravel',
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(1);

        Document::where('slug', 'intro-to-laravel')->update(['draft' => true]);

        $response = $this->getJson(route('prezet.search', [
            'q' => 'Introduction to Laravel',
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    private function seedTestData(): void
    {
        $doc = Document::factory()->create(
            [
                'slug' => 'intro-to-laravel',
                'category' => 'Web Development',
                'draft' => false,
                'frontmatter' => [
                    'slug' => 'intro-to-laravel',
                    'title' => 'Introduction to Laravel',
                    'excerpt' => 'Learn the basics of Laravel framework',
                    'tags' => ['PHP', 'Laravel', 'Framework'],
                    'hash' => md5('Introduction to Laravel'),
                    'image' => null,
                    'date' => now()->subDays(10)->toIso8601String(),
                    'updatedAt' => now()->subDays(10)->toIso8601String(),
                ],
            ]);

        Heading::create([
            'document_id' => $doc->id,
            'text' => $doc->frontmatter->title,
            'level' => 1,
            'section' => $doc->frontmatter->title,
        ]);
    }
}
