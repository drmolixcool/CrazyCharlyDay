<?php

declare(strict_types=1);

namespace App\dispatch;



class Dispatcher
{
    private ?string $action = null;

    public function __construct(string $action)
    {
        $this->action = $action;
    }


    public function run(): void
    {


    }



    private function render(string $template): void
    {

    }


}
