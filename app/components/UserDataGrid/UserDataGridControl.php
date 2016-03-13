<?php
/**
 * Created by PhpStorm.
 * User: matous
 * Date: 6.3.16
 * Time: 16:34
 */

namespace App\Components\UserDataGrid;

use App\Components\BaseGridControl;
use Mesour\DataGrid\Sources\IGridSource;


/**
 * @author Matouš Němec <http://mesour.com>
 *
 * @method void onStatusChange($id, $status)
 * @method void onEdit($id)
 * @method void onSomeAction($id)
 */
class UserDataGridControl extends BaseGridControl
{

	public $onStatusChange = [];

	public $onEdit = [];

	public $onSomeAction = [];

	public function attached($presenter)
	{
		parent::attached($presenter);

		$grid = $this->getGrid();

		$wrapper = $grid->getWrapperPrototype();

		$wrapper->class('my-class');
		// TRUE = append
		$wrapper->class('my-next-class', true);

		$grid->enablePager(8);

		$grid->enableFilter();

		$selection = $grid->enableRowSelection();
		$selectionLinks = $selection->getLinks();
		$selectionLinks->addHeader('Active');
		$selectionLinks->addLink('Active')
			->onCall[] = function () {
			dump('ActivateSelected', func_get_args());
		};
		$selectionLinks->addLink('Unactive')
			->setAjax(false)
			->onCall[] = function () {
			dump('InactivateSelected', func_get_args());
		};
		$selectionLinks->addDivider();
		$selectionLinks->addLink('Delete')
			->setConfirm('Really delete all selected users?')
			->onCall[] = function () {
			dump('DeleteSelected', func_get_args());
		};

		$grid->enableSortable('sort');

		$grid->enableEditable();

		$grid->enableExport(__DIR__ . '/../../../temp/cache');

		$status = $grid->addStatus('action', 'S');

		$status->addButton('active')
			->setStatus(1, 'Active', 'All active')
			->setIcon('check-circle-o')
			->setType('success')
			->setAttribute('href', $status->link('toActive!', [
				'id' => '{id}',
			]));

		$status->addButton('unactive')
			->setStatus(0, 'Unactive', 'All unactive')
			->setIcon('times-circle-o')
			->setType('danger')
			->setAttribute('href', $status->link('toUnactive!', [
				'id' => '{id}',
			]));

		$grid->addImage('avatar', 'Avatar')
			->setPreviewPath('preview', __DIR__ . '/../../../www', __DIR__ . '/../../../www/')
			->setMaxHeight(60)
			->setMaxWidth(60);

		$grid->addText('name', 'Name');

		$grid->addText('email', 'E-mail');

		$grid->addText('group_name', 'Group');

		$grid->addNumber('amount', 'Amount')
			->setUnit('CZK');

		$grid->addDate('last_login', 'Last login')
			->setFormat('Y-m-d - H:i:s');

		$container = $grid->addContainer('test_container', 'Actions');

		$button = $container->addButton('test_button');
		$button->setIcon('pencil')
			->setType('primary')
			->setAttribute('title', 'Edit')
			->setAttribute('href', $button->link('edit!', [
				'id' => '{id}',
			]));

		$dropDown = $container->addDropDown('test_drop_down')
			->setPullRight()
			->setAttribute('class', 'dropdown');

		$dropDown->addHeader('Test header');

		$first = $dropDown->addButton();
		$first->setText('First button')
			->setAttribute('href', $dropDown->link('someAction!', [
				'id' => '{id}',
			]));

		$dropDown->addDivider();

		$dropDown->addHeader('Test header 2');

		$dropDown->addButton()
			->setText('Second button')
			->setConfirm('Test confirm :-)')
			->setAttribute('href', $dropDown->link('someAction!', [
				'id' => '{id}',
			]));

		$dropDown->addButton()
			->setText('Third button')
			->setAttribute('href', $dropDown->link('someAction!', [
				'id' => '{id}',
			]));

		$mainButton = $dropDown->getMainButton();
		$mainButton->setText('Actions')
			->setType('danger');
	}

	public function setSource(IGridSource $source)
	{
		$source->setPrimaryKey('id');

		parent::setSource($source);
	}

	public function handleToUnactive($id)
	{
		$this->onStatusChange($id, 0);
	}

	public function handleToActive($id)
	{
		$this->onStatusChange($id, 1);
	}

	public function handleEdit($id)
	{
		$this->onEdit($id);
	}

	public function handleSomeAction($id)
	{
		$this->onSomeAction($id);
	}

}
