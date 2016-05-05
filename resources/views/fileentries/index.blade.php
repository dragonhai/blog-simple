@extends('app')
@section('content')
<link rel="stylesheet" href="<?php echo asset('vendor/dropzoner/dropzone/dropzone.min.css'); ?>">
@include('dropzoner::dropzone')

 
 <h1> Pictures list</h1>
 <div class="row">
        <ul class="thumbnails">
 @foreach($entries as $entry)
            <div class="col-md-2">
                <div class="thumbnail">
                    <img src="{{route('getentry', $entry->filename)}}" alt="ALT NAME" class="img-responsive" />
                    <div class="caption">
                        <p>{{$entry->original_filename}}</p>
                    </div>
                </div>
            </div>
 @endforeach
 </ul>
 </div>
 
<script src="<?php echo asset('vendor/dropzoner/dropzone/dropzone.min.js'); ?>"></script>
<script src="<?php echo asset('vendor/dropzoner/dropzone/config.js'); ?>"></script>
@endsection

@section('test')
{!! Form::open(['url' => route('addentry'), 'method'=>'POST', 'files'=>true]) !!}
    {!! Form::file('images[]', ['multiple'=>true]) !!}
    {!! Form::submit('Submit') !!}
{!! Form::close() !!}
@endsection