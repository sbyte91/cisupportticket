<?php

namespace App\Controllers;

use App\Models\SupportTicket;

class DashboardController extends BaseController
{
    public function index(): string
    {   
        $tickets = new SupportTicket();
        $low = $tickets->where('support_condition_id',1)->countAllResults();
        $medium = $tickets->where('support_condition_id',2)->countAllResults();
        $high = $tickets->where('support_condition_id',3)->countAllResults();
        $critical = $tickets->where('support_condition_id',4)->countAllResults();

        $pending = $tickets->where('ticket_status_id',1)->countAllResults();
        $processing = $tickets->where('ticket_status_id',2)->countAllResults();
        $resolved = $tickets->where('ticket_status_id',3)->countAllResults();

        $total_tickets = $tickets->countAllResults();

        $data['totaltickets'] = $total_tickets;
        $data['totallow'] = $low;
        $data['totalmedium'] = $medium;
        $data['totalhigh'] = $high;
        $data['totalcritical'] = $critical;
        $data['totalpending'] = $pending;
        $data['totalprocessing'] = $processing;
        $data['totalresolved'] = $resolved;

        return view('dashboard/index',$data);
    }
}
