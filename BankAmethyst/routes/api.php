<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/amethyst/login','ClientController@loginUser');
Route::get('/amethyst/login','ClientController@loginUser');


Route::post('/amethyst/getAccounts','ClientAccountController@getAccounts');
Route::get('/amethyst/getAccounts','ClientAccountController@getAccounts');


Route::post('/amethyst/calculateAccountNumber','AccountController@calculateAccountNumberReq');

Route::post('/amethyst/newestTransfers','TransferController@getNewestTransfers');
Route::post('/amethyst/transferHistory','TransferController@getTransfers');
Route::post('/amethyst/transferHistoryDates','TransferController@getTransfersDatetoDate');
Route::get('/amethyst/internalTransfer','TransferController@internalTransfer');
Route::post('/amethyst/internalTransfer','TransferController@internalTransfer');
Route::post('/amethyst/expressTransfer','TransferController@expressTransfer');
Route::get('/amethyst/transfer','TransferController@transfer');
Route::post('/amethyst/transfer','TransferController@transfer');
Route::get('/amethyst/getTransferToVerification','TransferController@getAllManualVerifications');
Route::post('/amethyst/getTransferToVerification','TransferController@getAllManualVerifications');
Route::post('/amethyst/sendXML','TransferController@sendFiles');
Route::post('/amethyst/expressAdin','TransferController@incomingExpressTransferFromAdin');

Route::get('/amethyst/verifyTransfer','VerificationController@manualVerification');
Route::post('/amethyst/verifyTransfer','VerificationController@manualVerification');

Route::post('/amethyst/przychodzacy','TransferController@przychodzacy');
