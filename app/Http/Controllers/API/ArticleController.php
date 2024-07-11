<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
class ArticleController extends Controller
{
    public function index()
    {
        return response()->json(Article::all(), Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string|max:255',
        ]);

        $article = Article::create($validated);

        if (isset($validated['tags'])) {
            $this->syncTags($article, $validated['tags']);
        }

        return response()->json($article, Response::HTTP_CREATED);
    }

    public function show(Article $article)
    {
        return response()->json($article, Response::HTTP_OK);
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string|max:255',
        ]);

        $article->update($validated);

        if (isset($validated['tags'])) {
            $this->syncTags($article, $validated['tags']);
        }

        return response()->json($article, Response::HTTP_OK);
    }

    private function syncTags(Article $article, array $tags)
    {
        $tagIds = [];
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }
        $article->tags()->sync($tagIds);
    }
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
