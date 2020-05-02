<?php

/**
 * Datatable Base Controller - For custom search 
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Datatable Base
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */


namespace App\Http\Controllers;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Query\Builder;
use Yajra\DataTables\DataTables;

use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\Services\DataTable;

use App\Http\Start\Helpers;
class DataTableBase extends DataTable {

    /*
     * The reason why this class exists is that Yajra/DataTables requires this service class for export methods to work
     * properly.
     */

    /** @var Builder The query that will be used to get the data from the db. */
    private $mQuery;

    /** @var array An array of columns */
    private $mColumns;

    /** @var BaseEngine The DataTable */
    private $mDataTable;

    /**
     * @param            $query
     * @param BaseEngine $dataTable
     * @param array      $columns
     */
    public function __construct($query, CollectionDataTable $dataTable, $columns,$filename) {
        
        // parent::__construct(app(DataTables::class), app(Factory::class));

        $this->mQuery = $query;
        $this->mColumns = $columns;
        $this->mDataTable = $dataTable;
        $this->filename = $filename.'-' .date('Y-m-d_H-i-s');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax() {
        return $this->mDataTable->make(true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query() {
        return $this->mQuery;
    }

    public function html() {
        return $this->builder()->columns($this->mColumns);
    }


}