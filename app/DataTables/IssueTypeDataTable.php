<?php

namespace App\DataTables;

use App\Models\IssueType;
use Yajra\DataTables\Services\DataTable;

class IssueTypeDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {

		return datatables($query)
			->addColumn('action', function ($query) {
				return '<a title="' . trans('admin_messages.edit') . '" href="' . route('admin.edit_issue_type', $query->id) . '" ><i class="material-icons">edit</i></a>&nbsp;<a title="' . trans('admin_messages.delete') . '" href="javascript:void(0)" class="confirm-delete" data-href="' . route('admin.delete_issue_type', $query->id) . '"><i class="material-icons">close</i></a>';
			})
			->addColumn('status', function ($query) {
				return $query->status_text;
			})
			->addColumn('user_type', function ($query) {
				return $query->user_type;
			});
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\User $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		return IssueType::get();
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['id','name','user_type','status'])
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
		return 'IssueType_' . date('YmdHis');
	}
}
