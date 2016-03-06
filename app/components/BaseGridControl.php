<?php

namespace App\Components;

use Mesour\Bridges\Nette\DataGridControl;
use Mesour\DataGrid\Sources\IGridSource;

abstract class BaseGridControl extends DataGridControl
{

	public function setSource(IGridSource $source)
	{
		$this->getGrid()->setSource($source);
	}

}
