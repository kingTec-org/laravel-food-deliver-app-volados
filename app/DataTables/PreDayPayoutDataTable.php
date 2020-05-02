<?php

namespace App\DataTables;

use App\Models\Payout;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Services\DataTable;

class PreDayPayoutDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {
		return datatables($query)
			->addColumn('day', function ($query) {
				foreach ($query as $key => $value) {
					return date('l', strtotime($value->created_at));
				}
				return '';
			})
			->addColumn('action', function ($query) {
				$date = '';
				foreach ($query as $key => $value) {
					
					$date = date('d-m-Y', strtotime($value->created_at));
					
				}

				return '<a title="' . trans('admin_messages.edit') . '" href="' . route('admin.payout_day', ['store_id' => $query[0]->user_id, 'date' => $date]) . '" ><i class="material-icons">library_books</i></a>';
			})
			->addColumn('total_amount', function ($query) {
				$total = 0;
				foreach ($query as $key => $value) {

					$total += $value->amount;

				}
				return $total;
			})
			->addColumn('payout_amount', function ($query) {
				$total = 0;
				foreach ($query as $key => $value) {

					if ($value->status) {
						$total += (float) $value->amount;
					}

				}
				return $total;
			});
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\User $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		$from = date('Y-m-d' . ' 00:00:00', strtotime(request()->start_date));
		$to = date('Y-m-d' . ' 23:59:59', strtotime(request()->end_date));
		 return $order = Payout::where('user_id', request()->user_id)->with('order')
			->whereHas('order', function ($query) {
				$query->history();
			})
			->whereBetween('created_at', array($from, $to))
			->get()
			->groupBy(function ($date) {
				return Carbon::parse($date->created_at)->format('l');
			});
			// dd($order);
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['day', 'total_amount', 'payout_amount'])
			->addAction(['width' => '80px', 'printable' => false])
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
		return 'PayoutDate' . date('YmdHis');
	}
}
