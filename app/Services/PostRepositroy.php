<?php

namespace App\Services;

use App\Events\PostPublishedEvent;
use App\Models\Post;
use Illuminate\Support\Arr;

class PostRepositroy
{
    protected $post;
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
    /**
     * Create Post
     * @param $data array
     * @return App\Models\Post
     */
    public function store($data)
    {
        $post =  $this->post->create($data);
        if ($post->status == Post::STATUS_PUBLISHED) {
            event(new PostPublishedEvent($post));
        }

        return $post;
    }

    /**
     * Get datatable data
     * @param $data array
     * @return App\Models\Post
     */

    function data($request)
    {
        $keyword = Arr::get($request, 'search.value', null);

        $perpage = (int) Arr::get($request, 'length', -1);
        $offset =  (int) Arr::get($request, 'start', 0);

        $sortOrder = Arr::get($request, 'order.0.dir', 'ASC');
        $sortBy = Arr::get($request, 'order.0.column', '');
        $sortBy = Arr::get($request, 'columns.' . $sortBy . '.data', 'id');

        $query = $this->post->select(['posts.id', 'posts.title', 'posts.status', 'websites.name'])
            ->join('websites', 'posts.website_id', '=', 'websites.id');
        $query->where('title', 'like', '%' . $keyword . '%');

        $count = clone $query;

        $query->offset($offset)->limit($perpage);
        $query->orderBy($sortBy, $sortOrder);
        $total =  $count->count();

        $result = array(
            "draw"            =>  Arr::get($request, 'draw', 1),
            "recordsTotal"    => $total,
            "recordsFiltered" => $total,
            "data"            => $query->get(),
        );

        return    $result;
    }
}
