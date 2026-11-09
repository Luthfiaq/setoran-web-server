<?php

// Front Controller: satu-satunya pintu masuk semua request API
require_once __DIR__ . '/../app/core/autoload.php';

(new Router())->dispatch();
