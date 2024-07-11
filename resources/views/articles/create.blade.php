@extends('layouts.app')

@section('content')
<div class="row">
    <h1>Create Article</h1>
    <form action="{{ route('articles.store') }}" method="POST" class="form-body">
        @csrf
        <div class="box-body ">
        <div class="form">
            <div class="form-group">
                <label for="title">Title</label>
                <input class="form-control" type="text" name="title" id="title">
            </div>
            <div class="form-group">
                <label for="body">Body</label>
                <textarea class="form-control" name="body" id="body"></textarea>
            </div>
            <div class="form-group">
                <label for="tags">Tags:</label>
                <input class="form-control" type="text" name="tags[]" id="tags" value="{{ old('tags') }}" placeholder="Enter tags separated by commas">
            </div>

            <button type="submit">Save</button>
        </div>
    </div>
    </form>
</div>
@endsection
