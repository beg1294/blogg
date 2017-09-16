@extends('main')
<?php $titleTag = htmlspecialchars($post->title);?>

@section('title',"| $titleTag")

@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <img src="{{ asset('images/' . $post->image) }}" height="200" width="400">
            <h1>{{ $post->title }}</h1>
            <p>{!! $post->body !!}</p>
            <hr>
            <p>Posted in {{ $post->category->name }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3 class="comments-title">
                <span class="glyphicon glyphicon-comment"></span>
                @if($post->comments()->count()>1)
                {{ $post->comments()->count() }} Comments
                @elseif($post->comments()->count()==1)
                    {{ $post->comments()->count() }} Comment
                @endif

            </h3>
            @foreach($post->comments as $comment)
                <div class="comment">
                    <div class="author-info">

                        <img src="{{"https://www.gravatar.com/avatar/" . md5(strtolower(trim($comment->email))) . "?s=50&d=retro"}}" class="author-image">

                        <div class="author-name">
                            <h4>{{ $comment->name }}</h4>
                            <p class="author-time">{{ date('M jS, Y - g:iA' , strtotime($comment->created_at)) }}</p>
                        </div>

                    </div>

                    <div class="comment-content">
                        {{ $comment->comment }}
                    </div>

                </div>
            @endforeach
        </div>
    </div>

    <div class="row">
        <div id="comment-form" class="col-md-8 col-md-offset-2 form-spacing-top">
            {{ Form::open(['route' => ['comments.store',$post->id],'method' =>'POST']) }}

            <div class="row">
                <div class="col-md-6">
                    {{ Form::label('name',"Name:") }}
                    {{ Form::text('name',null,['class' => 'form-control']) }}
                </div>

                <div class="col-md-6">
                    {{ Form::label('email',"Email:") }}
                    {{ Form::text('email',null,['class' => 'form-control']) }}
                </div>

                <div class="col-md-12">
                    {{ Form::label('comment',"Comment:") }}
                    {{ Form::textarea('comment', null, ['class' => 'form-control', 'rows'=>'5']) }}

                    {{ Form::submit('Add Comment', ['class'=>'btn btn-success btn-block form-spacing-top']) }}
                </div>
            </div>
        </div>
    </div>

    @endsection