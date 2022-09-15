<?php

Route::group(['namespace' => 'Botble\Historypayments\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'historypayments', 'as' => 'historypayments.'], function () {
            Route::resource('', 'HistorypaymentsController')->parameters(['' => 'historypayments']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'HistorypaymentsController@deletes',
                'permission' => 'historypayments.destroy',
            ]);
        });
    });

});
