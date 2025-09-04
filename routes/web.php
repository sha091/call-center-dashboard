<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\CallFlowController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\WorkCodeController;
use App\Http\Controllers\CallAgentController;
use App\Http\Controllers\ClientListController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WorkingHoursController;
use App\Http\Controllers\GenrateOutboundController;

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

// Route::get('/', function () {
//     return view('auth.login');
// });

Route::controller(UserController::class)->group(function(){
    Route::get('/','index')->name('auth.name');
    Route::get('login','index')->name('login');
    Route::post('postLogin','postLogin')->name('login.post');
    Route::get('logout','logOut')->name('logout');
});

//Auth::routes();

//ROLE SUPERVISOR
Route::group(['middleware' => ['auth','role:Supervisor']], function () {

    Route::controller(UserController::class)->group(function(){
        Route::get('user','userList')->name('user.list');
        //Route::get('user/registration','registration')->name('user.registration');
        Route::post('user/registration','user_registration')->name('user.registration');
        Route::post('user/change/password','change_password')->name('user.change.password');
        Route::get('dashboard','view_dashboard')->name('user.dashboard');
        Route::get('callCenterStatus','call_center_status')->name('call.center.status');
        Route::get('updateAdminAndGetQueueStats','updateAdminAndGetQueueStats')->name('update.and.get.queue');
        Route::get('updateBreakTime','updateBreakTime')->name('update.break.time');
        Route::post('user/status/update','user_status_update')->name('user.status.update');

    });

    Route::controller(CallAgentController::class)->group(function(){
        Route::get('callAgent','home')->name('call.agent.home');
        Route::get('callAgent/agent_stats','agent_stats')->name('call.agent.stats');
        Route::get('callAgent/queue_stats','queue_stats')->name('queue.agent.stats');
        Route::get('callAgent/received_call_stats','received_call_stats')->name('received.call.stats');
        Route::get('callAgent/dropped_call_stats','dropped_call_stats')->name('dropped.call.stats');
        Route::get('callAgent/off_time_stats','off_time_stats')->name('off.time.stats');
        Route::get('callCenterWallboard/wallboard','wallboard')->name('call.center.wallboard');
        Route::get('callAgentStatisticsSummary/agentStatisticsSummary','agent_statistics_summary')->name('agent.statistics.summary');

    });

    Route::controller(ReportsController::class)->group(function(){
        Route::get('reports/call_records','call_records')->name('call.records');
        Route::get('reports/export/call_records','export_call_records')->name('export.call.records');
        Route::get('reports/outbound','outbound_records')->name('outbound.reports');
        Route::get('reports/export/outbound','export_outbound_records')->name('export.outbound.reports');
        Route::get('reports/inbound','inbound_records')->name('inbound.reports');
        Route::get('reports/export/inbound','export_inbound_records')->name('export.inbound.reports');
        Route::get('reports/AgentProductivityReport','agent_pd_home')->name('agent.productivity.home');
        Route::get('repotrs/productivityWorkingTime','workingTime')->name('agent.productivity.working.time');
        Route::get('repotrs/productivityBreakTime','breakTimeSummary')->name('agent.productivity.break.time');
        Route::get('repotrs/productivityCallAndBusy','onCallBuzyTime')->name('agent.productivity.call.buzy.time');
        Route::get('repotrs/productivityBT','breakTimes')->name('agent.productivity.b.t');
        Route::get('reports/abandonCalls','abandonCalls')->name('agent.abandon.calls');
        Route::get('reports/missedCall','misscallRecords')->name('missed.call.reports');
        Route::get('reports/export/missedCall','exportMisscallRecords')->name('export.missed.call.reports');
        Route::get('reports/transferredCalls','transferredCallsReport')->name('transferred.call.report');
        Route::get('reports/export/transferredCalls','ExportTransferredCallsReport')->name('export.transferred.call.report');
        Route::get('reports/agentSummary','agentSummaryReport')->name('agent.summary.reports');
        Route::get('reports/offTime','off_time_report')->name('off.time.reports');
        Route::get('reports/feedback','feedback_report')->name('feedback.report');
        Route::get('reports/export/feedback','export_feedback_report')->name('export.feedback.reports');

    });

    Route::controller(GenrateOutboundController::class)->group(function(){
        Route::get('outbound','home')->name('outbound.home');
        Route::post('outboundCall','genrateCall')->name('outbound.call');
        Route::get('transferCall','transferCall')->name('outbound.transferCall');
    });

    Route::controller(WorkCodeController::class)->group(function(){
        Route::get('workcode','home')->name('workcode.home');
        Route::post('insertCallWorkCode','insertCallWorkCode')->name('insert.call.workcode');
        Route::post('workcode/status/update','workcode_status_update')->name('workcode.status.update');
        Route::post('workcode/addWorkCode','addNewCallWorkCode')->name('add.workcode');
    });

    Route::controller(NotificationController::class)->group(function(){
        Route::get('notification/list','notification_list')->name('notification.list');
        Route::get('notification','notification')->name('notification');
        Route::post('notification/add','notification_add')->name('notification.add');
        Route::get('notification/alert','fetch_notification_alert')->name('fetch.notification.alert');
        // Route::get('chat','home');
    });

    Route::controller(WorkingHoursController::class)->group(function(){
        Route::get('settings/workingHours','show_working_hours')->name('settings.working.hours');
        Route::get('settings/getWorkingHours/{cc_id}','view_working_hours')->name('settings.getWorkingHours');
        Route::get('settings/resetWorkingHours/{cc_id}','reset_working_hours')->name('settings.resetWorkingHours');
        Route::POST('settings/updateWorkingHours','update_working_hours')->name('settings.update.WorkingHours');
    });

    Route::controller(CampaignController::class)->group(function(){
        Route::get('campaign','home')->name('campaign');
        Route::post('campaign-create','CreateCampaign')->name('campaign.create');
        Route::get('campaign/download/file', 'getDownload')->name('campaign.download.file');
        Route::post('change/campaign/status','updateCampaignStatus')->name('update.campaign.status');
        Route::post('uploadPrompt','uploadPrompt')->name('upload.prompt');
        Route::post('uploadPrevPrompt','uploadPreviousPrompt')->name('upload.prev.prompt');
    });

    Route::controller(CallFlowController::class)->group(function(){
        Route::get('call-flow/sound-settings','home')->name('sound-settings');
        Route::post('call-flow/upload-sound-setting','uploadSounds')->name('upload-sound-setting');
        Route::post('call-flow/upload-off-time-sound','uploadOfftime')->name('upload-off-time-sound');
        Route::get('call-flow/create-flow','showFlow')->name('create-flow');
        Route::get('call-flow/queue','queue')->name('queue');
        Route::post('call-flow/add-queue','createQueue')->name('add-queue');
        Route::post('call-flow/add-call-flow','create_call_flow')->name('add-call-flow');
        Route::get('call-flow/showqueue/{queue_id}','showqueue')->name('show-queue');
        Route::post('call-flow/update-queue','updateQueue')->name('update-queue');
        Route::get('call-flow/delete-queue/{queue_id}','delete')->name('delete-queue');
    });



});

//ROLE AGENT
Route::group(['middleware' => ['auth','role:Agents']], function () {

    Route::controller(UserController::class)->group(function(){
        Route::get('Agent/dashboard','view_dashboard')->name('agent.user.dashboard');
        Route::get('Agent/callCenterStatus','call_center_status')->name('agent.call.center.status');
        Route::get('Agent/updateAdminAndGetQueueStats','updateAdminAndGetQueueStats')->name('agent.update.and.get.queue');
        Route::get('Agent/updateBreakTime','updateBreakTime')->name('agent.update.break.time');
    });

    Route::controller(CallAgentController::class)->group(function(){
        Route::get('Agent/callAgent','home')->name('agent.call.agent.home');
        Route::get('Agent/callAgent/agent_stats','agent_stats')->name('agent.call.agent.stats');

    });

    Route::controller(ReportsController::class)->group(function(){
        Route::get('Agent/reports/missedCall','misscallRecords')->name('agent.missed.call.reports');
        Route::get('Agent/reports/export/missedCall','exportMisscallRecords')->name('agent.export.missed.call.reports');
        Route::get('Agent/reports/agentSummary','agentSummaryReport')->name('agents.agent.summary.reports');
        Route::get('Agent/reports/offTime','off_time_report')->name('agents.off.time.reports');
    });

    Route::controller(GenrateOutboundController::class)->group(function(){
        Route::get('Agent/outbound','home')->name('agent.outbound.home');
        Route::post('Agent/outboundCall','genrateCall')->name('agent.outbound.call');
        Route::get('Agent/transferCall','transferCall')->name('agent.outbound.transferCall');
    });

    Route::controller(WorkCodeController::class)->group(function(){
        Route::post('Agent/insertCallWorkCode','insertCallWorkCode')->name('agent.insert.call.workcode');
    });

    Route::controller(NotificationController::class)->group(function(){
        Route::get('Agent/notification/alert','fetch_notification_alert')->name('agent.fetch.notification.alert');
    });

    Route::controller(ClientListController::class)->group(function(){
        Route::get('Agent/client/data','home')->name('client');
        Route::get('Agent/check/session','checkSessionStatus')->name('check.session.status');
        Route::post('Agent/client/','insertClientData')->name('insert.client.data');
    });

});

//Role Super Admin
Route::group(['middleware' => ['auth','role:SuperAdmin']], function () {

    Route::controller(UserController::class)->group(function(){
        Route::get('SuperAdmin/dashboard','super_admin_dashboard')->name('super.admin.dashboard');
    });

    Route::controller(CompanyController::class)->group(function(){
        Route::get('SuperAdmin/company','home')->name('home.company');
        Route::get('SuperAdmin/create/company','createCompany')->name('create.company');
        Route::post('SuperAdmin/store/company','storeCompny')->name('store.company');
        Route::post('SuperAdmin/Change/company/Status','changeStatus')->name('change.status');
        Route::post('SuperAdmin/Change/autodetection/Status','changeAutoDetectionStatus')->name('change.auto.detection.status');
        Route::get('SuperAdmin/cdrs/search', 'search')->name('cdrs.search');
        Route::get('SuperAdmin/download/file', 'getDownload')->name('download.file');


    });

});
