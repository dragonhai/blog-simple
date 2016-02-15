@extends('app')

@section('title')
Edit Post
@endsection

@section('content')
<script type="text/javascript" src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
	tinymce.init({
		selector : "textarea",
		plugins : ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste jbimages"],
		toolbar : "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages"
	}); 
</script>

{!! Form::open(['url' => '/update']) !!}
	<input type="hidden" name="post_id" value="{{ $post->id }}{{ old('post_id') }}">
	<div class="form-group">
		{{--*/ $title = !old('title') ? $post->title : old('title') /*--}}
		{!! Form::text('title', $title, ['required' => 'required', 'placeholder' => 'Enter title here', 'class' => 'form-control']) !!}
	</div>
	<div class="form-group">
		{{--*/ $body = !old('body') ? $post->body : old('body') /*--}}
		{!! Form::textarea('body', $body, ['class' => 'form-control']) !!}
	</div>
	<div class="form-group">
		{{--*/ $published_at = !old('published_at') ? $post->published_at : old('published_at') /*--}}
		{!! Form::input('date', 'published_at', date('Y-m-d'), ['class' => 'form-control']) !!}
	</div>
	<div class="form-group">
		{!! Form::select('tag_list[]', $tags, $tag_list, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
	</div>
	{{--*/ $submitText = $post->active ? 'Update' : 'Publish' /*--}}
	{!! Form::submit($submitText, ['name' => 'publish', 'class' => 'btn btn-success']) !!}
	{!! Form::submit('Save As Draft', ['name' => 'save', 'class' => 'btn btn-default']) !!}
	{!! HTML::link(url('delete/'.$post->id.'?_token='.csrf_token()), 'Delete', ['class' => 'btn btn-danger']) !!}
{!! Form::close() !!}
@endsection
