<?php

namespace Giritli\Tests\Enum;

use Giritli\Enum\Enum;

class StatusNoDefaultEnum extends StatusEnum
{
	const processing = 'processing';

	protected $default = null;
}