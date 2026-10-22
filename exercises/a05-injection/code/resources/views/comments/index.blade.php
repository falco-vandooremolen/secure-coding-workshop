{{-- INTENTIONALLY INSECURE - workshop training material. Never run or deploy. --}}
@foreach ($comments as $comment)
    <div class="comment">
        <strong>{{ $comment->author }}</strong>
        <p>{!! $comment->body !!}</p>
    </div>
@endforeach
