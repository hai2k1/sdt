<?php

namespace Botble\Bycode\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Bycode\Repositories\Interfaces\BycodeInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;

class HistoryByCodeTable extends TableAbstract
{
    protected $hasOperations= false;

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = false;

    /**
     * BycodeTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param BycodeInterface $bycodeRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, BycodeInterface $bycodeRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $bycodeRepository;

    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'name_app',
                'phone_number',
                'code',
                'id_user',
                'created_at',
                'status',
            ])->where('id_user',Auth::user()->id)->whereNotNull('code')->orderBy('id', 'DESC')->limit(10);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'name_app' => [
                'title' => 'Tên app',
                'class' => 'text-start',
            ],
            'phone_number' => [
                'title' => 'Số điện thoại',
                'class' => 'text-start',
            ],
            'code' => [
                'title' => 'Code',
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('bycode.deletes'), 'bycode.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
//    public function getBulkChanges(): array
//    {
//        return [
//            'name' => [
//                'title'    => trans('core/base::tables.name'),
//                'type'     => 'text',
//                'validate' => 'required|max:120',
//            ],
//            'status' => [
//                'title'    => trans('core/base::tables.status'),
//                'type'     => 'select',
//                'choices'  => BaseStatusEnum::labels(),
//                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
//            ],
//            'created_at' => [
//                'title' => trans('core/base::tables.created_at'),
//                'type'  => 'date',
//            ],
//        ];
//    }

    /**
     * @return array
     */
}
