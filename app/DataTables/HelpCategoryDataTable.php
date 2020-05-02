<?php

/**
 * Help Category DataTable
 *
 * @package     Gofereats
 * @subpackage  DataTable
 * @category    Help Category
 * @author      Trioangle Product Team
 * @version     1.5.8.2
 * @link        http://trioangle.com
 */


namespace App\DataTables;

use App\Models\HelpCategory;
use Yajra\DataTables\Services\DataTable;

class HelpCategoryDataTable extends DataTable
{
   /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($help_category) {

        return datatables($help_category)
            ->addColumn('status', function ($help_category) {
                return $help_category->status_text;
            })
            ->addColumn('type', function ($help_category) {
                return $help_category->type_text;
            })
            ->addColumn('action', function ($help_category) {
                return '<a title="' . trans('admin_messages.edit_help_category') . '" href="' . route('admin.edit_help_category', $help_category->id) . '" ><i class="material-icons">edit</i></a>&nbsp;<a title="' . trans('admin_messages.delete_help_category') . '" href="javascript:void(0)" class="confirm-delete" data-href="' . route('admin.delete_help_category', $help_category->id) . '"><i class="material-icons">close</i></a>';
            });
           
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return HelpCategory::get();

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns(['id','name','description','type','status'])
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
    protected function filename()
    {
        return 'help_category_' . date('YmdHis');
    }
}
