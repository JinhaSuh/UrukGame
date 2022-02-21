<?php

namespace MyApp\Controller;

class MyController
{
    protected $ci;
    //Constructor
    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
    }

    public function method1($request, $response, $args) {
        $response->getBody()->write("Hello jinha world!");
        return $response;
    }
}
