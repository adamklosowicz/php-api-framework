<?php

declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use MicroApiFramework\Application;

$configPath = __DIR__ . '/../resources/config.yml';

Application::run($configPath);

