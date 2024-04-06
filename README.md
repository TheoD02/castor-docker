# Castor Docker

This repository contains a set of classes for interacting with Docker containers in PHP trough Castor.

## Description

Classes and helpers provide you an API to interact with Docker containers in PHP.

Classes reflect the Docker CLI commands.

Some helper like `docker()->utils()->isRunningInsideContainer()` can be used to detect if the PHP script is running
inside a Docker container.

## Usage

```php
import('composer://theod02/castor-class-task');

// context() can be provided to docker() to use a specific context (is not a required argument)
docker(context())->compose()->exec(
    service: 'my-service',
    args: ['ls', '-la'],
    user: 'www-data',
    workdir: '/var/www/html',
);
```

This repository provide a `RunnerTrait` that can be used to run commands automatically in a Docker container from host
or from a Docker container directly.

Example classe for running `composer` commands :

```php
class Composer
{
    use RunnerTrait {
        __construct as private __runnerTraitConstruct;
    }

    public function __construct(
        private readonly Context $context,
        ?string $workingDirectory = null
    ) {
        $this->addIf($workingDirectory, '--working-dir', $workingDirectory);
        $this->__runnerTraitConstruct($context);
    }

    protected function getBaseCommand(): string
    {
        return 'composer';
    }

    protected function allowRunningUsingDocker(): bool
    {
        return true;
    }

    public function createProject(string $name, string $path): Process
    {
        $this->add('create-project', $name, $path);

        return $this->runCommand();
    }

    public function install(): Process
    {
        return $this->add('install')->runCommand();
    }

    public function require(string|array $packages, bool $dev = false, bool $withDependencies = false): Process
    {
        $packages = is_string($packages) ? [$packages] : $packages;
        $this->addIf($dev, '--dev');
        $this->addIf($withDependencies, '--with-dependencies');

        return $this->add('require', ...$packages)->runCommand();
    }

    public function update(
        string|array|null $packages = null,
        bool $dev = false,
        bool $withDependencies = false
    ): Process {
        $packages = is_string($packages) ? [$packages] : ($packages ?? []);
        $this->addIf($dev, '--dev');
        $this->addIf($withDependencies, '--with-all-dependencies');

        return $this->add('update', ...$packages)->runCommand();
    }
}

function composer(?Context $context = null, ?string $workingDirectory = null): Composer
{
    return new Composer(context: $context ?? context(), workingDirectory: $workingDirectory);
}

// Usage
composer()->install();
composer()->require('symfony/console', dev: true);
```

