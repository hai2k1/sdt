<?php

namespace Botble\Bycode\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Bycode\Http\Requests\BycodeRequest;
use Botble\Bycode\Repositories\Interfaces\BycodeInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Bycode\Tables\BycodeTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Bycode\Forms\BycodeForm;
use Botble\Base\Forms\FormBuilder;

class BycodeController extends BaseController
{
    /**
     * @var BycodeInterface
     */
    protected $bycodeRepository;

    /**
     * @param BycodeInterface $bycodeRepository
     */
    public function __construct(BycodeInterface $bycodeRepository)
    {
        $this->bycodeRepository = $bycodeRepository;
    }

    /**
     * @param BycodeTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(BycodeTable $table)
    {
        page_title()->setTitle(trans('plugins/bycode::bycode.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/bycode::bycode.create'));

        return $formBuilder->create(BycodeForm::class)->renderForm();
    }

    /**
     * @param BycodeRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(BycodeRequest $request, BaseHttpResponse $response)
    {
        $bycode = $this->bycodeRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(BYCODE_MODULE_SCREEN_NAME, $request, $bycode));

        return $response
            ->setPreviousUrl(route('bycode.index'))
            ->setNextUrl(route('bycode.edit', $bycode->id))
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
        $bycode = $this->bycodeRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $bycode));

        page_title()->setTitle(trans('plugins/bycode::bycode.edit') . ' "' . $bycode->name . '"');

        return $formBuilder->create(BycodeForm::class, ['model' => $bycode])->renderForm();
    }

    /**
     * @param int $id
     * @param BycodeRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, BycodeRequest $request, BaseHttpResponse $response)
    {
        $bycode = $this->bycodeRepository->findOrFail($id);

        $bycode->fill($request->input());

        $bycode = $this->bycodeRepository->createOrUpdate($bycode);

        event(new UpdatedContentEvent(BYCODE_MODULE_SCREEN_NAME, $request, $bycode));

        return $response
            ->setPreviousUrl(route('bycode.index'))
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
            $bycode = $this->bycodeRepository->findOrFail($id);

            $this->bycodeRepository->delete($bycode);

            event(new DeletedContentEvent(BYCODE_MODULE_SCREEN_NAME, $request, $bycode));

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
            $bycode = $this->bycodeRepository->findOrFail($id);
            $this->bycodeRepository->delete($bycode);
            event(new DeletedContentEvent(BYCODE_MODULE_SCREEN_NAME, $request, $bycode));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
