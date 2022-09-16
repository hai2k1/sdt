<?php

namespace Botble\Historypayments\Tables;

use Illuminate\Support\Facades\Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Historypayments\Repositories\Interfaces\HistorypaymentsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;

class HistorypaymentsTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * HistorypaymentsTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param HistorypaymentsInterface $historypaymentsRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, HistorypaymentsInterface $historypaymentsRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $historypaymentsRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('historypayments.edit')) {
                    return $item->name;
                }
                return Html::link(route('historypayments.edit', $item->id), $item->name);
            })
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
                return $this->getOperations('historypayments.edit', 'historypayments.destroy', $item);
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
                'name_bank_account',
                'bank_id',
                'user_id',
                'bank_name',
                'money',
                'created_at',
                'status',
            ]);
        if(Auth::user()->isSuperUser()){
            return $this->applyScopes($query);
        }
        $query->where('user_id', Auth::user()->id);
        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'user_id' => [
                'title' => 'id người dùng',
                'class' => 'text-start',
            ],
            'name_bank_account' => [
                'title' => 'tên tài khoản ngân hàng',
                'class' => 'text-start',
            ],
            'bank_id' => [
                'title' => 'id bank ',
                'class' => 'text-start',
            ],
            'bank_name' => [
                'title' => 'tên ngân hàng',
                'class' => 'text-start',
            ],
            'money' => [
                'title' => 'tiền',
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
    public function buttons()
    {

        return $this->addCreateButton(route('historypayments.create'), 'historypayments.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        if (Auth::user()->hasAnyPermission(['historypayments.edit', 'historypayments.destroy'])) {
            return $this->addDeleteAction(route('historypayments.deletes'), 'historypayments.destroy', parent::bulkActions());
        }
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }
}
