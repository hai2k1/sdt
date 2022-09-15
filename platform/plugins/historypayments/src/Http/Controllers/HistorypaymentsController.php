<?php

namespace Botble\Historypayments\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Historypayments\Http\Requests\HistorypaymentsRequest;
use Botble\Historypayments\Repositories\Interfaces\HistorypaymentsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Historypayments\Tables\HistorypaymentsTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Historypayments\Forms\HistorypaymentsForm;
use Botble\Base\Forms\FormBuilder;

class HistorypaymentsController extends BaseController
{
    /**
     * @var HistorypaymentsInterface
     */
    protected $historypaymentsRepository;

    /**
     * @param HistorypaymentsInterface $historypaymentsRepository
     */
    public function __construct(HistorypaymentsInterface $historypaymentsRepository)
    {
        $this->historypaymentsRepository = $historypaymentsRepository;
    }

    /**
     * @param HistorypaymentsTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(HistorypaymentsTable $table)
    {
        page_title()->setTitle(trans('plugins/historypayments::historypayments.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/historypayments::historypayments.create'));

        return $formBuilder->create(HistorypaymentsForm::class)->renderForm();
    }

    /**
     * @param HistorypaymentsRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(HistorypaymentsRequest $request, BaseHttpResponse $response)
    {
        $historypayments = $this->historypaymentsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(HISTORYPAYMENTS_MODULE_SCREEN_NAME, $request, $historypayments));

        return $response
            ->setPreviousUrl(route('historypayments.index'))
            ->setNextUrl(route('historypayments.edit', $historypayments->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $historypayments = $this->historypaymentsRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $historypayments));

        page_title()->setTitle(trans('plugins/historypayments::historypayments.edit') . ' "' . $historypayments->name . '"');

        return $formBuilder->create(HistorypaymentsForm::class, ['model' => $historypayments])->renderForm();
    }

    /**
     * @param int $id
     * @param HistorypaymentsRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, HistorypaymentsRequest $request, BaseHttpResponse $response)
    {
        $historypayments = $this->historypaymentsRepository->findOrFail($id);

        $historypayments->fill($request->input());

        $historypayments = $this->historypaymentsRepository->createOrUpdate($historypayments);

        event(new UpdatedContentEvent(HISTORYPAYMENTS_MODULE_SCREEN_NAME, $request, $historypayments));

        return $response
            ->setPreviousUrl(route('historypayments.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $historypayments = $this->historypaymentsRepository->findOrFail($id);

            $this->historypaymentsRepository->delete($historypayments);

            event(new DeletedContentEvent(HISTORYPAYMENTS_MODULE_SCREEN_NAME, $request, $historypayments));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $historypayments = $this->historypaymentsRepository->findOrFail($id);
            $this->historypaymentsRepository->delete($historypayments);
            event(new DeletedContentEvent(HISTORYPAYMENTS_MODULE_SCREEN_NAME, $request, $historypayments));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
