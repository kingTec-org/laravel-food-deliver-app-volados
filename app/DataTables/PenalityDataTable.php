<?php

namespace App\DataTables;

use App\Models\Penality;
use Yajra\DataTables\Services\DataTable;

class PenalityDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {

		return datatables($query)
			->addColumn('amount', function ($query) {
				return currency_symbol().$query->amount;
			})
			->addColumn('user_name', function ($query) {
				return $query->user_name;
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
		return  Penality::get();
		// dd($data->user->name);
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['id','user_name','amount','paid_amount','remaining_amount','user_type'])
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
		return 'Penality_' . date('YmdHis');
	}
}
