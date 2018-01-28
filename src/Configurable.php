<?php

namespace Gietos\Model;

interface Configurable
{
    /**
     * @param string|array $config
     */
    public function configure($config);
}
