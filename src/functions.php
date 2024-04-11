<?php

namespace TheoD02\Castor\Docker;

use Castor\Context;
use function Castor\context;

function docker(?Context $context = null): Docker
{
    return new Docker($context ?? context());
}
