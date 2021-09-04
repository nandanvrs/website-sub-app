<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

use App\Services\WebsiteRepository;

class WebsiteController extends Controller
{
    public function __construct(
        WebsiteRepository $service
    ) {
        $this->service = $service;
    }


    /**
     * List Websites
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return  $this->service->data($request->all());
    }

    /**
     * Subscribe Websites
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:124',
            'website_id' => 'required|integer',
            'name' => 'required|max:124',
        ]);
        try {
            $response =  $this->service->subscribe($request->only('email', 'website_id', 'name'));
            return $this->successResponse($response);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), [], 400);
        }
    }
}
