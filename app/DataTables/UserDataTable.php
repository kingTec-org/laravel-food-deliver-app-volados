<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {

		return datatables($query)
			->addColumn('action', function ($query) {
				return '<a title="' . trans('admin_messages.edit') . '" href="' . route('admin.edit_user', $query->id) . '" ><i class="material-icons">edit</i></a>&nbsp;<a title="' . trans('admin_messages.delete') . '" href="javascript:void(0)" class="confirm-delete" data-href="' . route('admin.delete_user', $query->id) . '"><i class="material-icons">close</i></a>';
			})
			->addColumn('user_status', function ($query) {
				return $query->user_status;
			});
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\User $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		return $val = User::get();

	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['id', 'name', 'email', 'date_of_birth'])
			->addColumn(['data' => 'user_status', 'name' => 'user_status', 'title' => 'Status'])
			->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'])
			->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'])
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
		return 'User_' . date('YmdHis');
	}
}
