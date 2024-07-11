@extends('layouts.app')

@section('content')
    <h1>Articles</h1>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th> topUsers</th>
                    <th>{{$topUsers->count()}}</th>
                </tr>
                <tr>
                    <th> mostCommentedArticles</th>
                    <th>{{$mostCommentedArticles->count()}}</th>
                </tr>
                <tr>
                    <th> totalArticlesPerUser</th>
                    <th>{{$totalArticlesPerUser->count()}}</th>
                </tr>
                </thead>
        </table>
    <div>
        <a class="btn btn-info" role="button" href="{{ route('articles.create') }}">Create Article</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th> title</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($articles as $article)
            <tr>
                <td>{{ $article->id }}</td>
                <td><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a></td>
                <td><a class="btn btn-success" role="button" href="{{ route('articles.edit', $article) }}">Edit</a>|
                    <form action="{{ route('articles.destroy', $article) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


@endsection
