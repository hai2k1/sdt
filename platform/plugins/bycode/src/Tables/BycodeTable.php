<?php

namespace Botble\Bycode\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Bycode\Repositories\Interfaces\BycodeInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;

class BycodeTable extends TableAbstract
{
    protected $hasOperations= false;

    /**
     * @var bool
     */
    protected $hasActions = false;

    /**
     * @var bool
     */
    protected $hasFilter = false;
    protected $view = 'plugins/bycode::table';

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

        if (!Auth::user()->hasAnyPermission(['bycode.edit', 'bycode.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
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
            })
            ->addColumn('operations', function ($item) {
                return true;
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
                'session',
                'id_user',
                'created_at',
                'status',
            ])->where('id_user',Auth::user()->id)->orderBy('id', 'DESC')->limit(10);

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
//    public function buttons()
//    {
//        return $this->addCreateButton(route('bycode.create'), 'bycode.create');
//    }

    /**
     * {@inheritDoc}
     */
//    public function bulkActions(): array
//    {
//        return $this->addDeleteAction(route('bycode.deletes'), 'bycode.destroy', parent::bulkActions());
//    }

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
//    public function getFilters(): array
//    {
//        return $this->getBulkChanges();
//    }
}
