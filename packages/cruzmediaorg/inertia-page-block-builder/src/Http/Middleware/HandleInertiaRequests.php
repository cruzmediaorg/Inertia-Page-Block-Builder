<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    public function rootView(Request $request)
    {
        return 'ipbb::app';
    }

    public function resolveComponent($name)
    {
        if (str_starts_with($name, 'IPBB::')) {
            return parent::resolveComponent(str_replace('IPBB::', '', $name));
        }

        return parent::resolveComponent($name);
    }

    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            // Add any shared data here
        ]);
    }
}