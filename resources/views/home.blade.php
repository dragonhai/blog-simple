@extends('app')

@section('title')
{{$title}}
@endsection


@section('content')

@if ( !$posts->count() )
There is no post till now. 
@if(Auth::guest())
Login and write a new post now!!!
@endif
@else
<div class="">
	@foreach( $posts as $post )

	<div class="list-group">
		<div class="list-group-item">
			<h3><a href="{{ route('post.show', [$post->slug]) }}">{{ $post->title }}</a>
				@if(!Auth::guest() && ($post->author_id == Auth::user()->id || Auth::user()->isAdmin()))
					@if($post->active == '1')
					<button class="btn btn-primary pull-right"><a href="{{ route('post.edit', [$post->slug])}}">Edit Post</a></button>
					@else
					<button class="btn btn-primary pull-right"><a href="{{ route('post.edit', [$post->slug])}}">Edit Draft</a></button>
					@endif
				@endif
			</h3>
			<p>{{ Carbon::parse($post->published_at)->format('M d,Y \a\t h:i a') }} By <a href="{{ url('/user/'.$post->author_id)}}">{{ $post->author->name }}</a></p>
			
		</div>
		<div class="list-group-item">
			<article>
				{!! str_limit($post->body, $limit = 1500, $end = '....... <a href=\'.url("/".$post->slug).\'>Read More</a>') !!}
			</article>
		</div>
	</div>
	@endforeach
	{!! $posts->render() !!}
</div>


@endif

@endsection
