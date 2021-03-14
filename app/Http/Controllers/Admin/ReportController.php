<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\MessageReport;
use App\Services\MessageService;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{

    /**
     * @function show report details
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function show (Request $request)
    {
        $reportId = $request->route('report');
        $report = MessageReport::find($reportId);
        $messageRecipients = $report->message->recipients->map(function($recipient) {
            return $recipient->player->ticker;
        });
        if (!$report) {
            return redirect()->back()
                ->with('status', __('admin.report.notfound'))
                ->with('severity', 'error');
        } else {
            return view('admin.reports.details', compact(
                'report', 'messageRecipients'
            ));
        }
    }

    /**
     * @function resolve a report
     * @param Request $request
     * @return RedirectResponse
     */
    public function resolve (Request $request): RedirectResponse
    {
        $u = new UserService;
        $m = new MessageService;
        $reportId = $request->route('report');
        $report = MessageReport::find($reportId);
        $duration = $request->input(['duration']);
        $reporteeMsg = $request->input(['reporteeMsg']);
        $reporterMsg = $request->input(['reporterMsg']);
        $reporter = Player::find($report->reporter_id);
        $validator = Validator::make($request->input(), [
            'reporteeMsg' => ['max:'.config('rules.reports.reportMessage.max')],
            'reporterMsg' => [
                'required',
                'string',
                'min:'.config('rules.reports.reportMessage.min'),
                'max:'.config('rules.reports.reportMessage.max')
            ],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            // if suspension, suspend player.
            if ($duration !== null) {
                $u->suspend($report->reportee->user_id, Auth::user(), $duration, $request->input(['reporteeMsg']));
                $report->suspension_duration = $duration;
            }
            // if message to reportee, send it.
            if ($reporteeMsg !== null) {
                $reportee = Player::find($report->reportee_id);
                $reporteeMessage = $m->sendAdminMessage(
                    $reportee,
                    'admin.report.reporteeMsg.subject',
                    $request->input(['reporteeMsg']),
                );
                $report->admin_reportee_message_id = $reporteeMessage->id;
            }
            // send admin message to reporter
            $reporterMessage = $m->sendAdminMessage($reporter, 'admin.report.reporterMsg.subject', $reporterMsg);

            // resolve the report
            $report->resolved_admin = Auth::user()->id;
            $report->admin_reporter_message_id = $reporterMessage->id;
            $report->save();

            // redirect back to details page.
            return back()
                ->with('status', __('admin.report.resolve.success'))
                ->with('severity', 'success');
        }
    }
}
