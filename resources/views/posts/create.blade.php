@extends('layouts.main')
@section('title', '新規登録')
@section('content')

    @include('partial.errors')
    @include('partial.flash')
    <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row m-3">
            <div class="mb-3">
                <label for="image" class="form-label">画像ファイルを選択してください</label>
                <input type="file" name="image[]" id="image" class="form-control" value="{{ old('file') }}" multiple>
            </div>
            <div class="mb-3">
                <label for="discription" class="form-label">イメージの概要を入力してください</label>
                <input type="text" name="discription" id="discription" class="form-control" value="{{ old('post') }}">
            </div>
        </div>
        <input type="submit">
    </form>
    </div>
@endsection
