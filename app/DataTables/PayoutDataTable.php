<?php

namespace App\DataTables;

use App\Models\Payout;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Services\DataTable;

class PayoutDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {
		return datatables($query)
			->addColumn('week_day', function ($query) {
				foreach ($query as $key => $value) {
					$week_no = 0;
					$year = date('Y', strtotime($value->created_at));
					$week_no = date('W', strtotime($value->created_at));
					$date = getWeekDates($year, $week_no);
					return date('d M', strtotime($date['week_start'])) . ' - ' . date('d M', strtotime($date['week_end']));
				}
				return '';
			})
			->addColumn('action', function ($query) {
				foreach ($query as $key => $value) {
					$week_no = 0;
					$year = date('Y', strtotime($value->created_at));
					$week_no = date('W', strtotime($value->created_at));
					$date = getWeekDates($year, $week_no);
					$start_date = date('d-m-Y', strtotime($date['week_start']));
					$end_date = date('d-m-Y', strtotime($date['week_end']));
				}

				return '<a title="' . trans('admin_messages.view') . '" href="' . route('admin.payout_per_day', ['store_id' => $query[0]->user_id, 'start_date' => $start_date, 'end_date' => $end_date]) . '" ><i class="material-icons">library_books</i></a>';
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

		return $order = Payout::where('user_id', request()->user_id)->with('order')
			->whereHas('order', function ($query) {
				$query->history();
			})
			->get()
			->groupBy(function ($date) {
				return Carbon::parse($date->created_at)->format('W');
			});
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['week_day', 'total_amount', 'payout_amount'])
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
		return 'week_payout' . date('YmdHis');
	}
}
