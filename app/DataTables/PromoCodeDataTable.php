<?php

namespace App\DataTables;

use App\Models\PromoCode;
use Yajra\DataTables\Services\DataTable;

class PromoCodeDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {

		return datatables($query)
			->addColumn('action', function ($query) {
				return '<a title="' . trans('admin_messages.edit') . '" href="' . route('admin.edit_promo', $query->id) . '" ><i class="material-icons">edit</i></a>&nbsp;<a title="' . trans('admin_messages.delete') . '" href="javascript:void(0)" class="confirm-delete" data-href="' . route('admin.delete_promo', $query->id) . '"><i class="material-icons">close</i></a>';
			})
			->addColumn('promo_status', function ($query) {
				return $query->promo_status;
			})
			->addColumn('percentage', function ($query) {
				return $query->percentage>0?$query->percentage:'';
			})
			->addColumn('promo_amount', function ($query) {
				return $query->price > 0 ? html_entity_decode($query->currency->code) . ' ' . $query->price : '';
			})
			->addColumn('promo_type_show', function ($query) {
				return $query->promo_type_show;
			});
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\User $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		return PromoCode::get();
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['id', 'code', 'start_date', 'end_date'])
			->addColumn(['data' => 'promo_amount', 'name' => 'promo_amount', 'title' => 'Amount'])
			->addColumn(['data' => 'percentage', 'name' => 'percentage', 'title' => 'percentage'])
			->addColumn(['data' => 'promo_type_show', 'name' => 'promo_type_show', 'title' => 'Promo Type'])
			->addColumn(['data' => 'promo_status', 'name' => 'promo_status', 'title' => 'Status'])
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
		return 'Promo_' . date('YmdHis');
	}
}
