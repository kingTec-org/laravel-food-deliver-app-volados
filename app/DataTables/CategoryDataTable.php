<?php

namespace App\DataTables;

use App\Models\Category;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {

		return datatables($query)
			->addColumn('action', function ($query) {
				return '<a title="' . trans('admin_messages.edit') . '" href="' . route('admin.edit_category', $query->id) . '" ><i class="material-icons">edit</i></a>&nbsp;<a title="' . trans('admin_messages.delete') . '" href="javascript:void(0)" class="confirm-delete" data-href="' . route('admin.delete_category', $query->id) . '"><i class="material-icons">close</i></a>';
			})
			->addColumn('is_top', function ($query) {
				$class = $query->is_top==1?"success":"danger";
				return '<a class="'.$class.'"  href="' . route('admin.is_top', ['id'=>$query->id,'column'=>'is_top']) . '" ><span>'.$query->is_top_status.'</span></a>';
			})
			->addColumn('most_popular', function ($query) {
				$class = $query->most_popular==1?"success":"danger";
				return '<a class="'.$class.'"  href="' . route('admin.most_popular', ['id'=>$query->id,'column'=>'most_popular']) . '" ><span>'.$query->most_popular_status.'</span></a>';
			})
			->addColumn('category_status', function ($query) {
				return $query->category_status;
			})
			->escapeColumns('is_top','most_popular');
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\User $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		return Category::get();
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->columns(['id', 'name','category_status','is_top','most_popular','created_at'])
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
		return 'Category_' . date('YmdHis');
	}
}
