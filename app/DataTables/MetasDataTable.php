<?php

namespace App\DataTables;

use App\Models\Metas;
use Yajra\DataTables\Services\DataTable;

class MetasDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {

		return datatables($query)
			->addColumn('action', function ($query) {
				return '<a title="' . trans('admin_messages.edit') . '" href="' . route('admin.meta_edit', $query->id) . '" ><i class="material-icons">edit</i></a>';
			})
			->addColumn('page', function ($query) {
				return $query->url;
			});
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\User $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		return Metas::get();
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['id'])
			->addColumn(['data' => 'page', 'name' => 'page', 'title' => 'page'])
			->addColumn(['data' => 'title', 'name' => 'title', 'title' => 'title'])
			->addColumn(['data' => 'description', 'name' => 'description', 'title' => 'description'])
			->addColumn(['data' => 'keywords', 'name' => 'keywords', 'title' => 'keywords'])
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
		return 'Metas_' . date('YmdHis');
	}
}
