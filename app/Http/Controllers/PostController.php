<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Models\Attachment;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        // dd($posts);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = new Post($request->all());
        $post->user_id = $request->user()->id;
        $files = $request->file('image');
        DB::beginTransaction();
        try {
            $post->save();

            if (!empty($files)) {
                $paths = [];
                foreach ($files as $file) {
                    $name = $file->getClientOriginalName();
                    $path = Storage::putFile('posts', $file);
                    if (!$path) {
                        throw new Exception('ファイルの保存に失敗しました');
                    }
                    $attachment = new Attachment([
                        'post_id' => $post->id,
                        'org_name' => $file->getClientOriginalName(),
                        'name' => basename($path),
                    ]);
                    $attachment->save();
                }

                DB::commit();
            }
        } catch (\Exception $e) {
            if (!empty($path)) {
                Storage::delete($path);
            }
            DB::rollback();
            return back()
                ->withErrors($e->getMessage());
        }

        return redirect()
            ->route('posts.index')
            ->with(['flash_message' => '登録が完了しました']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post = Post::with(['user'])->find($post->id);
        $comments = $post->comments()->latest()->get()->load(['user']);
        return view('posts.show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $post->fill($request->all());
        // dd($request->all());
        try {
            $post->save();
        } catch (\Exception $e) {
            return back()
                ->withErrors($e->getMessage());
        }

        return redirect(route('posts.index'))->with(['flash_message' => '更新が完了しました']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $path = $post->image_path;
        DB::beginTransaction();
        try {
            $post->delete();
            // // $post->attachment->deleat();
            if (!Storage::delete($path)) {
                throw new Exception('ファイルの削除に失敗しました。');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->withErrors($e->getMessage());
        }

        return redirect()->route('posts.index')
            ->with(['flash_message' => '削除しました']);
    }
}
