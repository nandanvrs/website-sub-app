<?php

namespace App\Services;


use App\Models\Website;
use Illuminate\Support\Arr;
use App\Models\Subscribers;
use App\Models\SubscribedWebsite;

class WebsiteRepository
{
    protected $website;
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    public function subscribe($data)
    {
        $email = Arr::get($data, 'email');
        $name = Arr::get($data, 'name');
        $website_id = Arr::get($data, 'website_id');
        $subscriber = Subscribers::where('email', $email)->first();
        if (is_null($subscriber)) {
            $subscriber =  Subscribers::create(['name' => $name, 'email' => $email]);
        }
        return SubscribedWebsite::updateOrCreate(
            ['website_id' =>   $website_id],
            ['subscriber_id' =>   $subscriber->id]
        );
    }


    /**
     * Get datatable data
     * @param $data array
     * @return App\Models\Website
     */

    function data($request)
    {
        $keyword = Arr::get($request, 'search.value', null);

        $perpage = (int) Arr::get($request, 'length', -1);
        $offset =  (int) Arr::get($request, 'start', 0);

        $sortOrder = Arr::get($request, 'order.0.dir', 'ASC');
        $sortBy = Arr::get($request, 'order.0.column', '');
        $sortBy = Arr::get($request, 'columns.' . $sortBy . '.data', 'id');

        $query =  $this->website->whereNotNull('websites.id');
        $query->where('name', 'like', '%' . $keyword . '%');
        $count = clone $query;

        $query->offset($offset)->limit($perpage);
        $query->orderBy($sortBy, $sortOrder);
        $total =  $count->count();


        $result = array(
            "draw"            =>  Arr::get($request, 'draw', 1),
            "recordsTotal"    => $total,
            "recordsFiltered" => $total,
            "data"            =>    $query->get(),
        );

        return    $result;
    }
}
