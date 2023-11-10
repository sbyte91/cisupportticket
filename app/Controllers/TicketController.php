<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Office;
use App\Models\SupportCondition;
use App\Models\SupportTicket;
use App\Models\SupportTicketResponse;
use App\Models\TicketNumberTemplate;
use App\Models\TicketStatus;
use App\Models\Users;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Database\MySQLi\Result;
use Config\Database;

class TicketController extends BaseController
{
    public function index()
    {
        $db = Database::connect();
        //$status = $db->table('ticket_status');
        //return $this->response->setStatusCode(Response::HTTP_OK)->setJSON();
        $status = new TicketStatus();
        $statuses = $status->select('ticket_status_id,ticket_status')->findAll();
        $office = new Office();
        $offices = $office->select('office_id,office_name')->findAll();
        $condition = new SupportCondition();
        $conditions = $condition->select('support_condition_id,condition')->findAll();
        return view('tickets/index',['statuses'=>$statuses,'offices'=>$offices,'conditions'=>$conditions]);
    }

    public function show($id = null)
    {
        $ticket = new SupportTicket();
        $data = $ticket->find($id);

        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($data);
    }

    public function list()
    {
        $postData = $this->request->getGet();

        $response = array();

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value'];
        $sortby = $postData['order'][0]['column']; // Column index
        $sortdir = $postData['order'][0]['dir']; // asc or desc
        $sortcolumn = $postData['columns'][$sortby]['data']; // Column name

        $ticket = new SupportTicket();
        $totalRecords = $ticket->select('support_ticket_id')->countAllResults();

        $totalRecordwithFilter = $ticket->select('support_ticket_id')
            ->orLike('ticket_num', $searchValue)
            ->orLike('requested_by', $searchValue)
            ->orLike('office_id', $searchValue)
            ->orLike('support_condition_id', $searchValue)
            ->orLike('description', $searchValue)
            ->orLike('acted_by', $searchValue)
            ->orLike('ticket_status_id', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();

        $records = $ticket->select('*')
            ->orLike('ticket_num', $searchValue)
            ->orLike('requested_by', $searchValue)
            ->orLike('office_id', $searchValue)
            ->orLike('support_condition_id', $searchValue)
            ->orLike('description', $searchValue)
            ->orLike('acted_by', $searchValue)
            ->orLike('ticket_status_id', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);


        $data = array();

        foreach ($records as $record) {
            $user_id = auth()->user()->id;
            $user_model = new Users();
            $status = new TicketStatus();
            $condition = new SupportCondition();
            $office = new Office();
            $prof = $user_model->select('username,secret')->join('auth_identities', '`auth_identities`.`user_id` = `users`.`id`')->where('`users`.id', $user_id)->findAll();
            $of = $office->select('office_name')->where('office_id', $record['office_id'])->findAll();
            $con = $condition->select('condition')->where('support_condition_id', $record['support_condition_id'])->findAll();
            $stat = $status->select('ticket_status')->where('ticket_status_id', $record['ticket_status_id'])->findAll();
            $data[] = array(
                "support_ticket_id" => $record['support_ticket_id'],
                "ticket_num" => $record['ticket_num'],
                "name" => $prof[0]['username'],
                "email" => $prof[0]['secret'],
                "office_id" => $record['office_id'],
                "office" => $of[0]['office_name'],
                "support_condition_id" => $record['support_condition_id'],
                "severity" => $con[0]['condition'],
                "ticket_status_id" => $record['ticket_status_id'],
                "status" => $stat[0]['ticket_status'],
                "description" => $record['description'],
            );
        }

        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordwithFilter,
            "data" => $data
        );

        return $this->response->setJson($response);
    }

    public function create()
    {
        $ticket = new SupportTicket();
        $request = $this->request->getJSON();
        
        $user = auth()->user()->id;
        $num = new TicketNumberTemplate();
        $template =  $num->select('*')->where('year',date('Y'))->findAll();

        if(isset($template[0]['number']) && !empty($template[0]['number'])){
            $cnt = (int) $template[0]['number'];
            $cur_num = $cnt+1;
            $ticket_num = date('Y').'-'.str_pad($cur_num,6,"0",STR_PAD_LEFT);
            $num->update($template[0]['ticket_num_template_id'], ['number'=>$cur_num]);
        } else {
            $num->insert(['number'=>1,'year'=>date('Y')]);
            $ticket_num = date('Y').'-'.str_pad(1,6,"0",STR_PAD_LEFT);
        }

        $data = [
            'ticket_num' => $ticket_num,
            'requested_by' => $user,
            'office_id' => $request->office_id,
            'support_condition_id' => $request->support_condition_id,
            'description' => $request->description,
            'ticket_status_id' => 1
        ];

        if (!$ticket->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $ticket->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }

        $ticket->insert($data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Ticket was successfully added.'
        );

        return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
    }

    public function update($id = null)
    {
        $ticket = new SupportTicket();
        $request = $this->request->getJSON();
        unset($request->id);

        $user = auth()->user()->id;
        $data = [
            'requested_by' => $user,
            'office_id' => $request->office_id,
            'support_condition_id' => $request->support_condition_id,
            'description' => $request->description
        ];
        
        if (!$ticket->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $ticket->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_NOT_MODIFIED)->setJSON($response);
        }

        $ticket->update($id, $data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Ticket updated successfully'
        );

        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
    }

    public function delete($id = null)
    {
        $response_ticket = new SupportTicketResponse();
        $check = $response_ticket->select('support_ticket_id')->where('support_ticket_id', $id)->countAllResults();

        if($check > 0) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => 'Ticket has an active related record'
            );
    
            return $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)->setJSON($response);
        } else {
            $ticket = new SupportTicket();
            if ($ticket->delete($id)) {
                $response = array(
                    'status' => 'success',
                    'error' => false,
                    'messages' => 'Ticket deleted successfully'
                );
    
                return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
            } else {
                $response = array(
                    'status' => 'error',
                    'error' => true,
                    'messages' => 'Record not found'
                );
                return $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->setJSON($response);
            }
        }
    }
}
