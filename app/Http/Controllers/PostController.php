<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.post.index');
    }

      /**
     * Create Post
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $websites = Website::select(['id','name'])->get();
        return view('admin.post.create', compact('websites'));
    }
}
