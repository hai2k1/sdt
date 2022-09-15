<?php

Route::group(['namespace' => 'Botble\Bycode\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'bycodes', 'as' => 'bycode.'], function () {
            Route::resource('', 'BycodeController')->parameters(['' => 'bycode']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'BycodeController@deletes',
                'permission' => 'bycode.destroy',
            ]);
            Route::resource('historybycode', 'HistoryByCodeController')->parameters(['' => 'historybycode']);
        });
    });

});
