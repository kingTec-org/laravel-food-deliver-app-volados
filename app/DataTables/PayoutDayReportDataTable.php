<?php

namespace App\DataTables;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Yajra\DataTables\Services\DataTable;

class PayoutDayReportDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {
		return datatables($query)
			->addColumn('id', function ($query) {
				return @$query->id;
			})
			->addColumn('payment_type', function ($query) {
				return @$query->payment_type_text;
			})
			->addColumn('user_name', function ($query) {
				return @$query->user->name;
			})
			->addColumn('driver_name', function ($query) {
				return @$query->driver->user->name;
			})
			->addColumn('store_name', function ($query) {
				return @$query->store->name;
			})
			->addColumn('earnings', function ($query) {
				return html_entity_decode(currency_symbol() . @$query->get_store_payout('amount'));
			})
			->addColumn('driver_earnings', function ($query) {
				return html_entity_decode(currency_symbol() . @$query->get_driver_payout('amount'));
			})
			->addColumn('status_text', function ($query) {
				return @$query->status_text;
			})
			->addColumn('payout_status_text', function ($query) {
				return @$query->get_store_payout('status_text');
			})
			->addColumn('driver_payout_status_text', function ($query) {
				return @$query->get_driver_payout('status_text');
			})
			->addColumn('action', function ($query) {
				return '<a title="' . trans('admin_messages.view') . '" href="' . route('admin.view_order', $query->id) . '" ><i class="material-icons">library_books</i></a>';

			});

	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\User $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		$from = date('Y-m-d' . ' 00:00:00', strtotime(request()->date));
		$to = date('Y-m-d' . ' 23:59:59', strtotime(request()->date));
		$user = User::find(request()->user_id);
		if ($user->type_text == 'store') {
			$user_id = Store::where('user_id', request()->user_id)->first()->id;
		} else {
			$user_id = $user->driver->id;
		}
		if ($user->type_text == 'store') {
			$order = Order::with('payout_table')->where('store_id', $user_id);
		} else {
			$order = Order::with('payout_table')->where('driver_id', $user_id);
		}
		return $order->whereBetween('created_at', array($from, $to))->history()
			->whereHas('payout_table', function ($query) {
				$query->where('user_id', request()->user_id);
			})
			->get();
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		$user = User::find(request()->user_id);
		if ($user->type_text == 'store') {
			return $this->builder()
				->columns(['id', 'payment_type', 'user_name', 'driver_name', 'earnings'])
				->addColumn(['data' => 'status_text', 'name' => 'status_text', 'title' => 'Status'])
				->addColumn(['data' => 'payout_status_text', 'name' => 'payout_status_text', 'title' => 'Payout Status'])
				->addAction(['width' => '80px', 'printable' => false])
				->parameters([
					'dom' => 'Bfrtip',
					'buttons' => ['csv','excel', 'print'],
				]);
		} else {
			return $this->builder()
				->columns(['id', 'payment_type', 'user_name', 'store_name', 'driver_earnings'])
				->addColumn(['data' => 'status_text', 'name' => 'status_text', 'title' => 'Status'])
				->addColumn(['data' => 'driver_payout_status_text', 'name' => 'driver_payout_status_text', 'title' => 'Payout Status'])
				->addAction(['width' => '80px', 'printable' => false])
				->parameters([
					'dom' => 'Bfrtip',
					'buttons' => ['csv','excel', 'print'],
			]);
		}
	}

	/**
	 * Get filename for export.
	 *
	 * @return string
	 */
	protected function filename() {
		return 'Payout_Per_Day' . request()->start_date . '-' . request()->end_date;
	}
}
