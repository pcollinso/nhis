<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['query_log']], function () {

  Route::get('test', ['uses' => '\App\Http\Controllers\BiometricController@test', 'as' => 'test']);

  Route::get('/user-perms/{id?}', ['uses' => '\App\Http\Controllers\PermissionController@userPermsGet', 'as' => 'user-perms']);
  Route::group(['middleware' => ['guest']], function () {
    Route::post('/vueLogin', ['uses' => '\App\Http\Controllers\Auth\LoginController@vueLogin', 'as' => 'vueLogin']);
    Route::get('/getPassword', ['uses' => '\App\Http\Controllers\UserController@getPassword', 'as' => 'getPassword']);
  });

  Route::get('biometric/identify/return-url', ['uses' => '\App\Http\Controllers\BiometricController@successIdentify', 'as' => 'biometric.successIdentify']);
  Route::get('biometric/identify/error-url', ['uses' => '\App\Http\Controllers\BiometricController@errorIdentify', 'as' => 'biometric.errorIdentify']);
  Route::get('biometric/verify/return-url', ['uses' => '\App\Http\Controllers\BiometricController@successVerify', 'as' => 'biometric.successVerify']);
  Route::get('biometric/verify/error-url', ['uses' => '\App\Http\Controllers\BiometricController@errorVerify', 'as' => 'biometric.errorVerify']);


  Route::group(['middleware' => ['auth']], function () {

    Route::get('getTP/{user_id}', ['uses' => '\App\Http\Controllers\UserController@getTP', 'as' => 'UserController@getTP']);
    Route::get('options/towns/{stateId}', ['uses' => '\App\Http\Controllers\OptionController@towns', 'as' => 'OptionController@towns']);
    Route::get('options/hcps/{key_name}/{key_id}', ['uses' => '\App\Http\Controllers\OptionController@hcps', 'as' => 'OptionController@hcps']);
    Route::get('codes/institution', ['uses' => '\App\Http\Controllers\InstitutionController@getInstitutionCode', 'as' => 'InstitutionController@getInstitutionCode']);
    Route::get('codes/hcp', ['uses' => '\App\Http\Controllers\HcpController@getHcpCode', 'as' => 'HcpController@getHcpCode']);
    Route::get('codes/hcp/{code}/treatments', ['uses' => '\App\Http\Controllers\TreatmentController@getTreatmentCode', 'as' => 'TreatmentController@getTreatmentCode']);
    Route::get('codes/user', ['uses' => '\App\Http\Controllers\UserController@getUserCode', 'as' => 'UserController@getUserCode']);
    Route::get('codes/contributions/batchCode/{m}/{y}', ['uses' => '\App\Http\Controllers\ContributionController@getBatchCode', 'as' => 'contributions.getBatchCode']);
    Route::post('nuban-check', ['uses' => '\App\Utilities\Utility@nubanCheck', 'as' => 'utility.nubanCheck']);
    Route::match(['get', 'post'], 'logout', ['uses' => '\App\Http\Controllers\Auth\LoginController@logout', 'as' => 'logout']);
    Route::get('/', ['uses' => '\App\Http\Controllers\DashboardController@index', 'as' => 'dashboard']);

    /**
     * biometrics
     */
    Route::match(['get', 'post'], 'biometric/user/{id?}', ['uses' => '\App\Http\Controllers\BiometricController@userBiometricData', 'as' => 'biometric/user']);
    Route::get(
      'biometric/identify/start',
      [
        'uses' => '\App\Http\Controllers\BiometricController@identify',
        'as' => 'biometric.identify',
        'middleware' => 'permission:agency-users:create,users:create,individual-contributors:create,institution-users:create,hcp-users:read'
      ]
    );
    Route::get(
      'biometric/verify/start/{id}',
      [
        'uses' => '\App\Http\Controllers\BiometricController@verify',
        'as' => 'biometric.verify',
        'middleware' => 'permission:agency-users:create,users:create,individual-contributors:create,institution-users:create,hcp-users:read'
      ]
    );



    /**
     * permissions
     */
    Route::get('/permissions', ['uses' => '\App\Http\Controllers\PermissionController@index', 'as' => 'permissions', 'middleware' => 'permission:permissions:manage']);
    Route::post('user-permissions-list', ['uses' => '\App\Http\Controllers\PermissionController@listUserPerms', 'as' => 'user-permissions-list', 'middleware' => 'permission:permissions:manage']);
    Route::post('user-permissions-add', ['uses' => '\App\Http\Controllers\PermissionController@addUserPerms', 'as' => 'user-permissions-add', 'middleware' => 'permission:permissions:manage']);
    Route::post('user-permissions-delete', ['uses' => '\App\Http\Controllers\PermissionController@deleteUserPerms', 'as' => 'user-permissions-delete', 'middleware' => 'permission:permissions:manage']);

    /**
     * institution-hcp
     */
    /*Route::get(
      'institution-hcp-delete',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@deleteHcp',
        'as' => 'InstitutionControler@deleteHcp',
        'middleware' => 'permission:institution-hcp:create'
      ]
    );*/
    Route::get(
      'institution-hcp/{id?}',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@listHcp',
        'as' => 'InstitutionControler@listHcp',
        'middleware' => 'permission:institution-hcp:read'
      ]
    );
    Route::post(
      'institution-hcp',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@addHcp',
        'as' => 'InstitutionControler@addHcp',
        'middleware' => 'permission:institution-hcp:create'
      ]
    );
    Route::delete(
      'institution-hcp/{id}',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@deleteHcp',
        'as' => 'InstitutionControler@deleteHcp',
        'middleware' => 'permission:institution-hcp:delete'
      ]
    );


    /**
     * users
     */
    Route::post(
      'users/search',
      [
        'uses' => '\App\Http\Controllers\UserController@searchUsers',
        'as' => 'UserController@searchUsers',
        'middleware' => 'permission:adoption:read,agency-users:read,users:read,individual-contributors:read,institution-users:read,hcp-users:read'
      ]
    );
    Route::get(
      'agency-users',
      [
        'uses' => '\App\Http\Controllers\UserController@agencyUsers',
        'as' => 'UserController@agencyUsers',
        'middleware' => 'permission:agency-users:read'
      ]
    );
    Route::post(
      'agency-users',
      [
        'uses' => '\App\Http\Controllers\UserController@createAgencyUser',
        'as' => 'UserController@createAgencyUser',
        'middleware' => 'permission:agency-users:create'
      ]
    );
    Route::put(
      'agency-users/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@updateAgencyUser',
        'as' => 'UserController@updateAgencyUser',
        'middleware' => 'permission:agency-users:update'
      ]
    );
    Route::delete(
      'agency-users/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@deleteAgencyUser',
        'as' => 'UserController@deleteAgencyUser',
        'middleware' => 'permission:agency-users:delete'
      ]
    );

    /**
     * contributors
     */
    Route::get(
      'individual-contributors',
      [
        'uses' => '\App\Http\Controllers\UserController@contributors',
        'as' => 'UserController@contributors',
        'middleware' => 'permission:individual-contributors:read'
      ]
    );
    Route::post(
      'individual-contributors',
      [
        'uses' => '\App\Http\Controllers\UserController@createIndividualContributor',
        'as' => 'UserController@createIndividualContributor',
        'middleware' => 'permission:individual-contributors:create'
      ]
    );
    Route::put(
      'individual-contributors/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@updateIndividualContributor',
        'as' => 'UserController@updateIndividualContributor',
        'middleware' => 'permission:individual-contributors:update'
      ]
    );
    Route::delete(
      'individual-contributors/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@deleteIndividualContributor',
        'as' => 'UserController@deleteIndividualContributor',
        'middleware' => 'permission:individual-contributors:delete'
      ]
    );

    /**
     * institutions
     */
    Route::get(
      'institutions',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@index',
        'as' => 'InstitutionController@index',
        'middleware' => 'permission:institutions:read'
      ]
    );
    Route::get(
      'institutions/search/{str}',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@search',
        'as' => 'InstitutionController@search',
        'middleware' => 'permission:institutions:read'
      ]
    );
    Route::post(
      'institutions',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@create',
        'as' => 'InstitutionController@create',
        'middleware' => 'permission:institutions:create'
      ]
    );

    Route::put(
      'institutions/{id}',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@update',
        'as' => 'InstitutionController@update',
        'middleware' => 'permission:institutions:update'
      ]
    );

    /**
     * hcps
     */
    Route::get(
      'hcps',
      [
        'uses' => '\App\Http\Controllers\HcpController@index',
        'as' => 'HcpController@index',
        'middleware' => 'permission:hcps:read'
      ]
    );
    Route::post(
      'hcps',
      [
        'uses' => '\App\Http\Controllers\HcpController@create',
        'as' => 'HcpController@create',
        'middleware' => 'permission:hcps:create'
      ]
    );
    Route::put(
      'hcps/{id}',
      [
        'uses' => '\App\Http\Controllers\HcpController@update',
        'as' => 'HcpController@update',
        'middleware' => 'permission:hcps:update'
      ]
    );

    /**
     * hcp users
     */
    Route::get(
      'hcps/{id}/users',
      [
        'uses' => '\App\Http\Controllers\UserController@hcpUsers',
        'as' => 'UserController@hcpUsers',
        'middleware' => 'permission:hcp-users:read'
      ]
    );
    Route::post(
      'hcp-users',
      [
        'uses' => '\App\Http\Controllers\UserController@createHcpUser',
        'as' => 'UserController@createHcpUser',
        'middleware' => 'permission:hcp-users:create'
      ]
    );
    Route::put(
      'hcp-users',
      [
        'uses' => '\App\Http\Controllers\UserController@updateHcpUser',
        'as' => 'UserController@updateHcpUser',
        'middleware' => 'permission:hcp-users:update'
      ]
    );
    Route::delete(
      'hcp-users/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@deleteHcpUser',
        'as' => 'UserController@deleteHcpUser',
        'middleware' => 'permission:hcp-users:delete'
      ]
    );
    /**
     * institution users
     */
    Route::get(
      'institutions/{id}/users',
      [
        'uses' => '\App\Http\Controllers\UserController@institutionUsers',
        'as' => 'UserController@institutionUsers',
        'middleware' => 'permission:institution-users:read'
      ]
    );
    Route::post(
      'institution-users',
      [
        'uses' => '\App\Http\Controllers\UserController@createInstitutionUser',
        'as' => 'UserController@createInstitutionUser',
        'middleware' => 'permission:institution-users:create'
      ]
    );
    Route::put(
      'institution-users',
      [
        'uses' => '\App\Http\Controllers\UserController@updateInstitutionUser',
        'as' => 'UserController@updateInstitutionUser',
        'middleware' => 'permission:institution-users:update'
      ]
    );
    Route::delete(
      'institution-users/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@deleteInstitutionUser',
        'as' => 'UserController@deleteInstitutionUser',
        'middleware' => 'permission:institution-users:delete'
      ]
    );

    /**
     * treatments
     */

    Route::get(
      'hcp/{id}/treatments',
      [
        'uses' => '\App\Http\Controllers\TreatmentController@index',
        'as' => 'treatment.index',
        'middleware' => 'permission:treatments:read'
      ]
    );
    Route::get(
      'treatments',
      [
        'uses' => '\App\Http\Controllers\TreatmentController@verify',
        'as' => 'treatment.verify',
        'middleware' => 'permission:treatments:create,treatments:read,treatments:verify'
      ]
    );

    Route::post(
      'treatments',
      [
        'uses' => '\App\Http\Controllers\TreatmentController@create',
        'as' => 'treatment.create',
        'middleware' => 'permission:treatments:create'
      ]
    );

    Route::post(
      'treatments/verify-confirm',
      [
        'uses' => '\App\Http\Controllers\TreatmentController@verifyConfirm',
        'as' => 'treatment.verifyConfirm',
        'middleware' => 'permission:treatments:verify-confirm'
      ]
    );

    Route::put(
      'treatments/{id}',
      [
        'uses' => '\App\Http\Controllers\TreatmentController@update',
        'as' => 'treatment.update',
        'middleware' => 'permission:treatments:update'
      ]
    );
    Route::delete(
      'treatments/{id}',
      [
        'uses' => '\App\Http\Controllers\TreatmentController@deleteTreatment',
        'as' => 'treatment.deleteTreatment',
        'middleware' => 'permission:treatments:delete'
      ]
    );

    /**
     * claims
     */
    Route::get(
      'claims/unpaid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@index',
        'as' => 'ClaimController@index',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );

    Route::get(
      'claims/paid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@paidClaims',
        'as' => 'ClaimController@paidClaims',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );

    Route::get(
      'state/{id}/hcps/claims/unpaid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@stateHcpsClaimsUnpaid',
        'as' => 'ClaimController@stateHcpsClaimsUnpaid',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );
    Route::get(
      'state/{id}/hcps/claims/paid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@stateHcpsClaimsPaid',
        'as' => 'ClaimController@stateHcpsClaimsPaid',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );

    Route::get(
      'hcp/{id}/treatments/claims/unpaid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@hcpTreatmentsClaimsUnpaid',
        'as' => 'ClaimController@hcpTreatmentsClaimsUnpaid',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );
    Route::get(
      'hcp/{id}/treatments/claims/paid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@hcpTreatmentsClaimsPaid',
        'as' => 'ClaimController@hcpTreatmentsClaimsPaid',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );

    Route::get(
      'institution/{id}/treatments/claims/unpaid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@institutionTreatmentsClaimsUnpaid',
        'as' => 'ClaimController@institutionTreatmentsClaimsUnpaid',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );
    Route::get(
      'institution/{id}/treatments/claims/paid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@institutionTreatmentsClaimsPaid',
        'as' => 'ClaimController@institutionTreatmentsClaimsPaid',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );

    Route::get(
      'my/treatments/claims',
      [
        'uses' => '\App\Http\Controllers\ClaimController@myTreatmentsClaims',
        'as' => 'ClaimController@myTreatmentsClaims',
      ]
    );
    Route::get(
      'my/treatments/claims/service',
      [
        'uses' => '\App\Http\Controllers\ClaimController@myTreatmentsClaimsService',
        'as' => 'ClaimController@myTreatmentsClaimsService',
      ]
    );

    /**
     * adoption
     */
    Route::get(
      'adoption/{id?}',
      [
        'uses' => '\App\Http\Controllers\AdoptionController@index',
        'as' => 'AdoptionController@index',
        'middleware' => 'permission:adoption:read'
      ]
    );
    Route::post(
      'adoption',
      [
        'uses' => '\App\Http\Controllers\AdoptionController@create',
        'as' => 'AdoptionController@create',
        'middleware' => 'permission:adoption:create'
      ]
    );

    Route::put(
      'adoption',
      [
        'uses' => '\App\Http\Controllers\AdoptionController@update',
        'as' => 'AdoptionController@update',
        'middleware' => 'permission:adoption:update'
      ]
    );
    Route::delete(
      'adoption/{id}',
      [
        'uses' => '\App\Http\Controllers\AdoptionController@deleteAdoption',
        'as' => 'AdoptionController@deleteAdoption',
        'middleware' => 'permission:adoption:delete'
      ]
    );

    /**
     * contributions
     */

    Route::get(
      'contributions',
      [
        'uses' => '\App\Http\Controllers\ContributionController@index',
        'as' => 'contributions.index',
        'middleware' => 'permission:contributions:read,contributions:process,contributions:approve,contributions:manage'
      ]
    );
    Route::post(
      'contributions/history',
      [
        'uses' => '\App\Http\Controllers\ContributionController@history',
        'as' => 'contributions.history',
        'middleware' => 'permission:contributions:read,contributions:manage'
      ]
    );
    Route::post(
      'contributions/process',
      [
        'uses' => '\App\Http\Controllers\ContributionController@process',
        'as' => 'contributions.process',
        'middleware' => 'permission:contributions:process,contributions:manage,contributions:read'
      ]
    );
    Route::post(
      'contributions/approve',
      [
        'uses' => '\App\Http\Controllers\ContributionController@approve',
        'as' => 'contributions.approve',
        'middleware' => 'permission:contributions:approve,contributions:manage,contributions:read'
      ]
    );
    Route::post(
      'contributions/pay',
      [
        'uses' => '\App\Http\Controllers\ContributionController@pay',
        'as' => 'contributions.pay',
        'middleware' => 'permission:contributions:pay,contributions:manage,contributions:read'
      ]
    );
    Route::post(
      'contributions/fetch/process',
      [
        'uses' => '\App\Http\Controllers\ContributionController@fetchProcess',
        'as' => 'contributions.fetch.process',
        'middleware' => 'permission:contributions:read,contributions:process,contributions:manage'
      ]
    );
    Route::post(
      'contributions/fetch/approve',
      [
        'uses' => '\App\Http\Controllers\ContributionController@fetchApprove',
        'as' => 'contributions.fetch.approve',
        'middleware' => 'permission:contributions:read,contributions:approve,contributions:manage'
      ]
    );
    Route::post(
      'contributions/process/do',
      [
        'uses' => '\App\Http\Controllers\ContributionController@doProcess',
        'as' => 'contributions.process.do',
        'middleware' => 'permission:contributions:process,contributions:manage,contributions:read'
      ]
    );
    Route::post(
      'contributions/approve/do',
      [
        'uses' => '\App\Http\Controllers\ContributionController@doApprove',
        'as' => 'contributions.approve.do',
        'middleware' => 'permission:contributions:approve,contributions:manage,contributions:read'
      ]
    );
    Route::post(
      'contributions/pay/do',
      [
        'uses' => '\App\Http\Controllers\ContributionController@doPay',
        'as' => 'contributions.pay.do',
        'middleware' => 'permission:contributions:pay,contributions:manage,contributions:read'
      ]
    );


  });
});

Auth::routes();
