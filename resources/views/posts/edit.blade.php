@extends('layouts.main')
@section('title', '編集画面')
@section('content')

    @include('partial.errors')
    @include('partial.flash')
    <section>
        <article class="card shadow">
            <figure class="m-3">
                <div class="row">
                    <div class="col-6">
                        <img src="{{ $post->image_url }}" width="100%">
                    </div>
                    <div class="col-6">
                        <figcaption>
                            <form action="{{ route('posts.update', $post) }}" method="post" id="form">
                                @csrf
                                @method('patch')
                                <div class="mb-3">
                                    <label for="discription" class="form-label">薬の概要を入力してください</label>
                                    <input type="text" name="discription" id="discription" class="form-control"
                                        value="{{ old('discription', $post->discription) }}">
                                </div>

                            </form>
                        </figcaption>
                    </div>
                </div>
            </figure>
        </article>
        <div class="d-grid gap-3 col-6 mx-auto btn-lg">
            <input type="submit" value="更新" form="form" class="btn btn-success btn-lg">
            <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-lg">戻る</a>
        </div>
    </section>
@endsection
