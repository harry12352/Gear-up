@extends('layouts.app')
@section('content')
    <div class="container text-center" data-username="">
        @foreach($page->files as $image)
            <img width="300px" height="auto" src="{{Storage::url($image->path)}}">
        @endforeach
        <br>
        <p>Title:{{$page->name}}<br></p>
        <p>Slug:{{$page->slug}}<br></p>
        <p>Description:{{$page->description}}</p>
    </div>
@endsection
<script>
</script>
