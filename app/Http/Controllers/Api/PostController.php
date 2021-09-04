<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;


use App\Services\PostRepositroy;

class PostController extends Controller
{
    public function __construct(
        PostRepositroy $service
    ) {
        $this->service = $service;
    }

    /**
     * List Posts
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return  $this->service->data($request->all());
    }

      /**
     * Publish Post
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:124',
            'website_id' => 'required|integer',
            'description' => 'required',
            'status' => 'required'
        ]);
        try {
            $this->service->store($request->only('title','website_id','description','status'));
            return $this->successResponse(null);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), [], 400);
        }
    }

   
}
