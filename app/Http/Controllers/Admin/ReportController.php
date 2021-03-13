<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageReport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $validator = Validator::make($request->input(), [
            'reporteeMsg' => ['max:'.config('rules.reports.reportMessage.max')],
            'reporterMsg' => ['required', 'string', 'min:'.config('rules.reports.reportMessage.min'), 'max:'.config('rules.reports.reportMessage.max')],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            dd($request->input());
            return back()
                ->with('status', __('admin.report.resolve.success'))
                ->with('severity', 'success');
        }
    }
}
