<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Office;
use App\Models\SupportCondition;
use App\Models\SupportTicket;
use App\Models\SupportTicketResponse;
use App\Models\TicketStatus;
use App\Models\Users;
use CodeIgniter\HTTP\Response;
use Config\Database;

class ResponseController extends BaseController
{
    public function index()
    {
        //$db = Database::connect();
        //$status = $db->table('ticket_status');
        //return $this->response->setStatusCode(Response::HTTP_OK)->setJSON();
        $status = new TicketStatus();
        //whereNotIn(?string $key = null, $values = null, ?bool $escape = null)
        $statuses = $status->select('ticket_status_id,ticket_status')->whereIn('ticket_status_id',[2,3])->findAll();
        $ticket_model = new SupportTicket();
        $tickets = $ticket_model->select('support_ticket_id,ticket_num')->whereIn('ticket_status_id',[1,2])->findAll();

        return view('responses/index',['statuses'=>$statuses,'tickets'=>$tickets]);
    }

    public function show($id = null)
    {
        $ticket = new SupportTicketResponse();
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

        $ticket = new SupportTicketResponse();
        $totalRecords = $ticket->select('support_ticket_response_id')->countAllResults();

        $totalRecordwithFilter = $ticket->select('support_ticket_response_id')
            // ->orLike('ticket_num', $searchValue)
            // ->orLike('requested_by', $searchValue)
            // ->orLike('office_id', $searchValue)
            // ->orLike('support_condition_id', $searchValue)
            // ->orLike('description', $searchValue)
            ->orLike('support_ticket_id', $searchValue)
            ->orLike('acted_by', $searchValue)
            ->orLike('ticket_status_id', $searchValue)
            ->orLike('remarks', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();

        $records = $ticket->select('*')
            ->orLike('support_ticket_id', $searchValue)
            ->orLike('acted_by', $searchValue)
            ->orLike('ticket_status_id', $searchValue)
            ->orLike('remarks', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);


        $data = array();

        foreach ($records as $record) {
            $user_id = $record['acted_by'];
            $user_model = new Users();
            $status = new TicketStatus();
            // $condition = new SupportCondition();
            // $office = new Office();
            $prof = $user_model->select('username,secret')->join('auth_identities', '`auth_identities`.`user_id` = `users`.`id`')->where('`users`.id', $user_id)->findAll();
            // $of = $office->select('office_name')->where('office_id', $record['office_id'])->findAll();
            // $con = $condition->select('condition')->where('support_condition_id', $record['support_condition_id'])->findAll();
            $stat = $status->select('ticket_status')->where('ticket_status_id', $record['ticket_status_id'])->findAll();
            $ticket = new SupportTicket();
            $tickets = $ticket->select('*')->where('support_ticket_id', $record['support_ticket_id'])->findAll();
            $data[] = array(
                "support_ticket_response_id" => $record['support_ticket_response_id'],
                "support_ticket_id" => $record['support_ticket_id'],
                "ticket_num" => $tickets[0]['ticket_num'],
                "acted_by" => $prof[0]['username'],
                //"email" => $prof[0]['secret'],
                //"office_id" => $record['office_id'],
                //"office" => $of[0]['office_name'],
                //"support_condition_id" => $record['support_condition_id'],
                //"severity" => $con[0]['condition'],
                "ticket_status_id" => $record['ticket_status_id'],
                "status" => $stat[0]['ticket_status'],
                "remarks" => $record['remarks'],
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
        $ticket = new SupportTicketResponse();
        $request = $this->request->getJSON();
        
        $user = auth()->user()->id;

        $check = $ticket->select('support_ticket_response_id')->where('ticket_status_id',3)->where('ticket_status_id',$request->support_ticket_id)->countAllResults();

        if($check > 0){
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => 'Ticket is already resolved.'
            );
    
            return $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)->setJSON($response);
        } else {
            $data = [
                'acted_by' => $user,
                'support_ticket_id' => $request->support_ticket_id,
                'remarks' => $request->remarks,
                'ticket_status_id' => $request->ticket_status_id
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
    }

    public function update($id = null)
    {
        $ticket = new SupportTicketResponse();
        $request = $this->request->getJSON();
        unset($request->id);

        $user = auth()->user()->id;
        $data = [
            'acted_by' => $user,
            'support_ticket_id' => $request->support_ticket_id,
            'remarks' => $request->remarks,
            'ticket_status_id' => $request->ticket_status_id
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
            'messages' => 'Ticket response updated successfully'
        );

        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
    }

    public function delete($id = null)
    {
        $response_ticket = new SupportTicketResponse();
        $check = $response_ticket->select('support_ticket_response_id')->where('support_ticket_response_id', $id)
            ->where('ticket_status_id', 3)->countAllResults();

        if($check > 0) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => 'Deletion of ticket response already resolved is not allowed.'
            );
    
            return $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)->setJSON($response);
        } else {
            $ticket = new SupportTicketResponse();
            if ($ticket->delete($id)) {
                $response = array(
                    'status' => 'success',
                    'error' => false,
                    'messages' => 'Ticket response deleted successfully'
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
