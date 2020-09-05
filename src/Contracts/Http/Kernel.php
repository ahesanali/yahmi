<?php

namespace Yahmi\Contracts\Http;

interface Kernel
{
    

    /**
     * Handle an incoming HTTP request.
     *
     * @param  $request_url
     * @return Response
     */
    public function hanldeRequest($request_url);

    
    /**
     * Get the YAHMI application instance.
     *
     * @return \Yahmi\Core\Application
     */
    public function getApplication();
}
