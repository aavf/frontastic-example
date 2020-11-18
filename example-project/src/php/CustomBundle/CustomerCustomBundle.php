<?php

namespace Customer\CustomBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CustomerCustomBundle extends Bundle
{
    /**
     * Compatibility with QafooLabs/NoFrameworkBundle
     *
     * @return null|string
     */
    public function getParent()
    {
        return null;
    }
}
