<?php

namespace App\DataTables;

use App\Models\Pages;
use Yajra\DataTables\Services\DataTable;

class PagesDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {

		return datatables($query)
			->addColumn('action', function ($query) {
				return '<a title="' . trans('admin_messages.edit') . '" href="' . route('admin.edit_static_page', $query->id) . '" ><i class="material-icons">edit</i></a>&nbsp;<a title="' . trans('admin_messages.delete') . '" href="javascript:void(0)" class="confirm-delete" data-href="' . route('admin.delete_static_page', $query->id) . '"><i class="material-icons">close</i></a>';
			})
			->addColumn('page_status', function ($query) {
				return $query->page_status;
			})
			->addColumn('user_page', function ($query) {
				return $query->user_page_text;
			})
			->addColumn('page_footer', function ($query) {
				return $query->page_footer;
			});
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\User $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query(Pages $model) {
		return Pages::get();
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['id', 'name', 'url','user_page'])
			->addColumn(['data' => 'page_footer', 'name' => 'page_footer', 'title' => 'Footer'])
			->addColumn(['data' => 'page_status', 'name' => 'page_status', 'title' => 'Status'])
			->addAction(['width' => '80px', 'printable' => false])
			->parameters([
				'order' => [0, 'desc'],
				'dom' => 'Bfrtip',
				'buttons' => ['csv','excel', 'print'],
			]);
	}

	/**
	 * Get filename for export.
	 *
	 * @return string
	 */
	protected function filename() {
		return 'Pages_' . date('YmdHis');
	}
}
