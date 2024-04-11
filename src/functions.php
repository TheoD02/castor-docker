<?php

namespace TheoD02\Castor\Docker\Functions;

use TheoD02\Castor\Docker\Docker;
use Castor\Context;

function docker(?Context $context = null): Docker
{
    return new Docker($context ?? context());
}
