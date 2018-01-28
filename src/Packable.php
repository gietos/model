<?php

namespace Gietos\Model;

interface Packable
{
    const RECURSION_LIMIT = 100;

    /**
     * Defines how object converts to array.
     *
     * @return array|string
     */
    public function pack();
}
