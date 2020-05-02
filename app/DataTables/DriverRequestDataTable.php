<?php

namespace App\DataTables;

use App\Models\Driver;
use App\Models\DriverRequest;
use Yajra\DataTables\Services\DataTable;

class DriverRequestDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {

		return datatables($query)

			->addColumn('pickup_location', function ($query) {
				return $query->pickup_location;
			})
			->addColumn('drop_location', function ($query) {
				return $query->drop_location;
			})
			->addColumn('status', function ($query) {
				return $query->status_text;
			});
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\User $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		$driver_id = Driver::where('user_id',request()->id)->first()->id;
		return DriverRequest::where('driver_id',$driver_id)->get();
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['id', 'pickup_location','drop_location','status','created_at'])
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
		return 'DriverRequest_' . date('YmdHis');
	}
}
