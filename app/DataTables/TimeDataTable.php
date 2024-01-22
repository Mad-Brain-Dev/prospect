<?php

namespace App\DataTables;

use App\Models\Suppression;
use App\Models\Time;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TimeDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($item) {
                $buttons = '';
                $buttons .= '<a class="dropdown-item" href="' . route('admin.times.search.download', $item->id) . '" title="Edit"><i class="mdi mdi-download-box-outline"></i> Download </a>';
//                $buttons .= '<a class="dropdown-item" href="' . route('admin.times.edit', $item->id) . '" title="Edit"><i class="mdi mdi-square-edit-outline"></i> Edit </a>';

                // TO-DO: need to chnage the super admin ID to 1, while Super admin ID will 1
                $buttons .= '<form action="' . route('admin.times.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post" style="">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="dropdown-item text-danger" onclick="return makeDeleteRequest(event, ' . $item->id . ')"  type="submit" title="Delete"><i class="mdi mdi-trash-can-outline"></i> Delete</button></form>
                        ';

                return '<div class="btn-group dropleft">
                <a href="#" onclick="return false;" class="btn btn-sm btn-dark text-white dropdown-toggle dropdown" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                <div class="dropdown-menu">
                ' . $buttons . '
                </div>
                </div>';
            })
            ->editColumn('created_at',function ($item){
                return custom_datetime($item->created_at);
            })
//            ->editColumn('status',function ($item){
//                $badge = $item->status == GlobalConstant::STATUS_ACTIVE ? "bg-success" : "bg-danger";
//                return '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
//            })
            ->editColumn('count',function ($item){
                return Suppression::where('time_id',$item->id)->count();
            })
            ->rawColumns(['action','count'])
            ->setRowId('id');

    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Time $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'DESC')->select('times.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('time-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->addAction(['width' => '55px', 'class' => 'text-center', 'printable' => false, 'exportable' => false, 'title' => 'Action']);
//             ->buttons([
//                        Button::make('excel'),
//                        Button::make('csv'),
//                        Button::make('pdf'),
//                        Button::make('print'),
//                        Button::make('reset'),
//                        Button::make('reload')
//                    ]);

    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {

        return [
            Column::make('name', 'first_name')->title('Name'),
            Column::make('created_at', 'created_at')->title('Date Crteated'),
            Column::make('count', 'count')->title('Count'),
            Column::make('csv_file_name', 'csv_file_name')->title('CSV File Name'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Timeshare_' . date('YmdHis');
    }
}
