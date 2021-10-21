@extends('layouts.main')
@section('title', '詳細画面')
@section('content')
    <h1>画像詳細</h1>
    <section>
        <article>
            <article class="card shadow position-relative">

                <figure class="m-3">
                    <div class="row">
                        <div class="col-6">

                            @foreach ($post->image_urls as $image_url)
                                <article class="w-full px-4 md:w-1/4 text-xl text-gray-800 leading-normal">
                                    <img class="w-full mb-2" src="{{ $image_url }}" alt="image">
                                    <img class="w-full mb-2" src="{{ $image_url }}" alt="image">
                                </article>
                            @endforeach
                        </div>
                        <div class="col-6">
                            <figcaption>
                                <h1>
                                    {{ $post->discription }}
                                </h1>

                            </figcaption>
                        </div>
                    </div>
                </figure>
                <a href="{{ route('posts.edit', $post) }}">
                    <i class="fas fa-edit position-absolute top-0 end-0 fs-1"></i>
                </a>
            </article>
    </section>
    </div>
    @auth
        <hr class="my-4">

        <div class="flex justify-end">
            <a href="{{ route('posts.comments.create', $post) }}"
                class="bg-indigo-400 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline block">コメント登録</a>
        </div>
    @endauth

    <section class="font-sans break-normal text-gray-900 ">
        @foreach ($comments as $comment)
            <div class="my-2">
                <span class="font-bold mr-3">{{ $comment->user->name }}</span>
                <span class="text-sm">{{ $comment->created_at }}</span>
                <p>{!! nl2br(e($comment->body)) !!}</p>
                <div class="flex justify-end text-center">
                    @can('update', $comment)
                        <a href="{{ route('posts.comments.edit', [$post, $comment]) }}"
                            class="text-sm bg-green-400 hover:bg-green-600 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline w-20 mr-2">編集</a>
                    @endcan
                    @can('delete', $comment)
                        <form action="{{ route('posts.comments.destroy', [$post, $comment]) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="削除" onclick="if(!confirm('削除しますか？')){return false};"
                                class="text-sm bg-red-400 hover:bg-red-600 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline w-20">
                        </form>
                    @endcan
                </div>
            </div>
            <hr>
        @endforeach
    </section>
    <form action="{{ route('posts.destroy', $post) }}" method="post" id="form">
        @csrf
        @method('delete')
    </form>
    <div class="d-grid col-6 mx-auto gap-3">
        <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-lg">戻る</a>
        <input type="submit" value="削除" form="form" class="btn btn-danger btn-lg"
            onclick="if (!confirm('本当に削除してよろしいですか？')) {return false};">
    </div>
@endsection
