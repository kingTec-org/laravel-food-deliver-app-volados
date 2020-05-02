<?php

namespace App\DataTables;

use App\Models\Order;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {
		return datatables($query)
			->addColumn('action', function ($query) {
				if (get_current_login_user() == 'admin') {
					return '<a title="' . trans('admin_messages.edit') . '"  href="' . route('admin.edit_order', $query->id) . '" ><i class="material-icons">edit</i></a>';
				} else {
					return '<a title="' . trans('admin_messages.edit') . '"  href="' . route('store.edit_order', $query->id) . '" ><i class="material-icons">edit</i></a>';
				}

			})
			->addColumn('payment_type', function ($query) {
				return $query->payment_type_show;
			})
			->addColumn('user_name', function ($query) {
				return $query->user->name;
			})
			->addColumn('store_name', function ($query) {
				return $query->store->name;
			})
			->addColumn('order_type', function ($query) {
				return $query->order_type_show;
			})
			->addColumn('total', function ($query) {
				return $query->total;
			})
			->addColumn('order_status', function ($query) {
				return $query->status_show;
			});
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\User $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		if (get_current_login_user() == 'admin') {
			return Order::get();
		} else if (get_current_login_user() == 'store') {
			$store_id = auth()->guard('store')->user()->id;
			return Order::where('store_id', $store_id)->get();
		}
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['id'])
			->addColumn(['data' => 'payment_type', 'name' => 'payment_type', 'title' => 'Payment Type'])
			->addColumn(['data' => 'user_name', 'name' => 'user_name', 'title' => 'User Name'])
			->addColumn(['data' => 'store_name', 'name' => 'store_name', 'title' => 'Store Name'])
			->addColumn(['data' => 'order_type', 'name' => 'order_type', 'title' => 'Order Type'])
			->addColumn(['data' => 'total', 'name' => 'total', 'title' => 'Total'])
			->addColumn(['data' => 'order_status', 'name' => 'order_status', 'title' => 'Status'])
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
		return 'Order_' . date('YmdHis');
	}
}
