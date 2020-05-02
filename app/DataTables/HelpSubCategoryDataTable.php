<?php

/**
 * Help Subcategory DataTable
 *
 * @package     Gofereats
 * @subpackage  DataTable
 * @category    Help Subcategory
 * @author      Trioangle Product Team
 * @version     1.5.8.2
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\HelpSubCategory;
use Yajra\DataTables\Services\DataTable;

class HelpSubCategoryDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($help_subcategory) {

        return datatables($help_subcategory)
            ->addColumn('status', function ($help_subcategory) {
                return $help_subcategory->status_text;
            })
            ->addColumn('category_name', function ($help_subcategory) {
                return $help_subcategory->category_name;
            })
            ->addColumn('action', function ($help_subcategory) {
                return '<a title="' . trans('admin_messages.edit_help_subcategory') . '" href="' . route('admin.edit_help_subcategory', $help_subcategory->id) . '" ><i class="material-icons">edit</i></a>&nbsp;<a title="' . trans('admin_messages.delete_help_subcategory') . '" href="javascript:void(0)" class="confirm-delete" data-href="' . route('admin.delete_help_subcategory', $help_subcategory->id) . '"><i class="material-icons">close</i></a>';
            });
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
       return HelpSubCategory::get();

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {

        return $this->builder()
                    ->columns(['id','category_name','name','description','status'])
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
        return 'help_subcategory' . date('YmdHis');
    }
}
