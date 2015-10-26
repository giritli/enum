<?php

namespace Giritli\Tests\Enum;

use Giritli\Enum\Enum;

class StatusEnum extends Enum
{
	const draft = 'draft';
	const active = 'active';
	const archived = 'archived';
	const cancelled = 'cancelled';

	protected $default = self::draft;
}