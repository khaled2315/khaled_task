@extends('layouts.app')

@section('content')
<div class="row">
    <h1>Edit Article</h1>
    <form action="{{ route('articles.update', $article) }}" method="POST" class="form-body">
        @csrf
        <div class="box-body ">
            <div class="form">
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input class="form-control"  type="text" name="title" id="title" value="{{ $article->title }}">
        </div>
        <div class="form-group">
            <label for="body">Body</label>
            <textarea class="form-control"  name="body" id="body">{{ $article->body }}</textarea>
        </div>
        <button type="submit">Update</button>
    </div>
</div>
    </form>

</div>
@endsection
