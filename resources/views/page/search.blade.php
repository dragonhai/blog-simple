@extends('app')

@section('content')
  @foreach( $posts as $post )
  <div class="list-group">
    <div class="list-group-item">
      <h3><a href="{{ route('post.show', [$post->slug]) }}">{{ $post->title }}</a>
        @if(!Auth::guest() && ($post->author_id == Auth::user()->id || Auth::user()->isAdmin()))
          @if($post->active == '1')
          <button class="btn pull-right"><a href="{{ route('post.edit', [$post->slug])}}">Edit Post</a></button>
          @else
          <button class="btn pull-right"><a href="{{ route('post.edit', [$post->slug])}}">Edit Draft</a></button>
          @endif
        @endif
      </h3>
      <p>{{ Carbon::parse($post->published_at)->format('M d,Y \a\t h:i a') }} By <a href="{{ url('/user/'.$post->author_id)}}">{{ $post->author->name }}</a></p>
      
    </div>
    <div class="list-group-item">
      <article>
        {!! str_limit($post->body, $limit = 1500, $end = '....... <a href='.url("/".$post->slug).'>Read More</a>') !!}
      </article>
    </div>
  </div>
  @endforeach
  {!! $posts->render() !!}
@endsection