<?php
declare(strict_types=1);

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->tag->prependTitle('Index -- '); // Current Page Title
    }

}

