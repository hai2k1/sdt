<?php

namespace Botble\Historypayments\Forms;

use Assets;
use Auth;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Historypayments\Http\Requests\HistorypaymentsRequest;
use Botble\Historypayments\Models\Historypayments;

class HistorypaymentsForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        Assets::addScripts(['input-mask']);
        $select = ['chưa duyệt'=>'chưa duyệt'];
        if (Auth::user()->hasAnyPermission(['GetStatus'])) {
            $select = BaseStatusEnum::labels();
        }
        $this
            ->setupModel(new Historypayments)
            ->setValidatorClass(HistorypaymentsRequest::class)
            ->withCustomFields()
            ->add('name_bank_account', 'text', [
                'label' => 'tên tài khoản ngân hàng',
                'label_attr' => [
                    'class' => 'control-label required'],
                'attr' => [
                    'placeholder' => 'tên tài khoản ngân hàng',
                    'data-counter' => 120,
                ],

            ])
            ->add('user_id', 'text', [
                'label' => 'user_id',
                'label_attr' => ['class' => 'control-label required d-none'],
                'attr' => [
                    'placeholder' => 'id bank',
                    'data-counter' => 120,
                    'class' => 'd-none',
                ],
                'default_value' => Auth::user()->id
            ])
            ->add('bank_id', 'text', [
                'label' => 'id bank',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => 'id bank',
                    'data-counter' => 120,
                ],
            ])
            ->add('bank_name', 'text', [
                'label' => 'tên ngân hàng',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => 'tên ngân hàng',
                    'data-counter' => 120,
                ],
            ])
            ->add('money', 'text', [
                'label' => 'tiền',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => 'tiền',
                    'data-counter' => 30,
                    'class' => 'form-control input-mask-number',
                ],
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',

                ],
                'choices' => $select,
            ])
            ->setBreakFieldPoint('status');
    }
}
