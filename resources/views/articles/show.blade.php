@extends('layouts.app')

@section('content')
    <h1>{{ $article->title }}</h1>
    <p>{{ $article->body }}</p>
    <a href="{{ route('articles.index') }}">Back to Articles</a>
@endsection
