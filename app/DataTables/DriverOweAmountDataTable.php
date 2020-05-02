<?php

namespace App\DataTables;

use App\Models\DriverOweAmount;
use Yajra\DataTables\Services\DataTable;

class DriverOweAmountDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {

		return datatables($query)
			->addColumn('paid_amount', function ($query) {
				return currency_symbol().$query->paid_amount;
			})
			->addColumn('driver_name', function ($query) {
				return $query->driver_name;
			})
			->addColumn('remaining_amount', function ($query) {
				return currency_symbol().$query->amount;
			});
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\User $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		return  DriverOweAmount::where('user_id','!=','')->get();
		// dd($data->user->name);
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['id','driver_name','paid_amount','remaining_amount'])
			->parameters([
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
		return 'DriverOweAmount_' . date('YmdHis');
	}
}
