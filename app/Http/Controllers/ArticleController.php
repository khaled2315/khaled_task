<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        //top 5 users with the most articles
        $topUsers = DB::table('users')
                    ->join('articles', 'users.id', '=', 'articles.user_id')
                    ->select('users.id', 'users.name', DB::raw('COUNT(articles.id) AS article_count'))
                    ->groupBy('users.id', 'users.name')
                    ->orderBy('article_count', 'DESC')
                    ->limit(5)
                    ->get();
        //10 most commented articles
        $mostCommentedArticles = DB::table('articles')
                                ->join('comments', 'articles.id', '=', 'comments.article_id')
                                ->select('articles.id', 'articles.title', DB::raw('COUNT(comments.id) AS comment_count'))
                                ->groupBy('articles.id', 'articles.title')
                                ->orderBy('comment_count', 'DESC')
                                ->limit(10)
                                ->get();
        //total number of articles per user
        $totalArticlesPerUser = DB::table('users')
                                ->leftJoin('articles', 'users.id', '=', 'articles.user_id')
                                ->select('users.id', 'users.name', DB::raw('COUNT(articles.id) AS total_articles'))
                                ->groupBy('users.id', 'users.name')
                                ->orderBy('total_articles', 'DESC')
                                ->get();
        //for filter if available
        $tag = $request->query('tag');
        if(!empty($tag)){
            $articles = Article::when($tag, function ($query) use ($tag) {
                $query->whereHas('tags', function ($query) use ($tag) {
                    $query->where('name', $tag);
                });
            })->get();
        }else{
            $articles = Article::all();
        }




        return view('articles.index', compact('articles','topUsers','mostCommentedArticles','totalArticlesPerUser'));
    }

    public function create()
    {
        return view('articles.create');
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
        $article->user_id = Auth::id();

        if (isset($validated['tags'])) {
            $this->syncTags($article, $validated['tags']);
        }

        return redirect()->route('articles.index');
    }

    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
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
        return redirect()->route('articles.index');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
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


}
