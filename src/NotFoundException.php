<?php

namespace Boke0\Rose;
use \Exception;
use \Boke0\Rose\ContainerException;
use \Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends ContainerException implements NotFoundExceptionInterface{}
