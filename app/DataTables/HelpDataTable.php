<?php

/**
 * Help DataTable
 *
 * @package     Gofereats
 * @subpackage  DataTable
 * @category    Help
 * @author      Trioangle Product Team
 * @version     1.5.8.2
 * @link        http://trioangle.com
 */


namespace App\DataTables;

use App\Models\Help;
use Yajra\DataTables\Services\DataTable;

class HelpDataTable extends DataTable
{
   
   /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($help) {

        return datatables($help)
            ->addColumn('status', function ($help) {
                return $help->status_text;
            })
            ->addColumn('category', function ($help) {
                return $help->category_name;
            })
            ->addColumn('sub_category', function ($help) {
                return $help->subcategory_name;
            })
            ->addColumn('type', function ($help) {
                return $help->category->type_text;
            })
            ->addColumn('action', function ($help) {
                return '<a title="' . trans('admin_messages.edit_help') . '" href="' . route('admin.edit_help', $help->id) . '" ><i class="material-icons">edit</i></a>&nbsp;<a title="' . trans('admin_messages.delete_help') . '" href="javascript:void(0)" class="confirm-delete" data-href="' . route('admin.delete_help', $help->id) . '"><i class="material-icons">close</i></a>';
            });
           
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Help::get();
    }

        /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns(['id','category','sub_category','type','question','status'])
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
        return 'help' . date('YmdHis');
    }
}
