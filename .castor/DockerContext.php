<?php

declare(strict_types=1);

class DockerContext
{
    public function __construct(
        public string $container,
        public string $serviceName,
        public string $workdir,
        public string $user = 'root',
        public bool $allowRunningInsideContainer = false,
    ) {
    }
}
