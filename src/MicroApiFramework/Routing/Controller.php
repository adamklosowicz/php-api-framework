<?php

namespace MicroApiFramework\Routing;

use MicroApiFramework\Http\Request;

class Controller {

    public function __construct(
        protected Request $request
    ) {}
}