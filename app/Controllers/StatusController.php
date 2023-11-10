<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupportTicket;
use App\Models\TicketStatus;
use CodeIgniter\HTTP\Response;

class StatusController extends BaseController
{
    public function index()
    {
        return view('status/index');
    }

    public function show($id = null)
    {
        $status = new TicketStatus();
        $data = $status->find($id);

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

        $status = new TicketStatus();
        $totalRecords = $status->select('ticket_status_id')->countAllResults();

        $totalRecordwithFilter = $status->select('ticket_status_id')
            ->orLike('ticket_status', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();

        $records = $status->select('*')
            ->orLike('ticket_status', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);


        $data = array();

        foreach ($records as $record) {
            $data[] = array(
                "ticket_status_id" => $record['ticket_status_id'],
                "ticket_status" => $record['ticket_status'],
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
        $status = new TicketStatus();
        $data = $this->request->getJSON();

        if (!$status->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $status->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }

        $status->insert($data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Status was successfully added.'
        );

        return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
    }

    public function update($id = null)
    {
        $status = new TicketStatus();
        $data = $this->request->getJSON();
        unset($data->id);
        
        if (!$status->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $status->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_NOT_MODIFIED)->setJSON($response);
        }

        $status->update($id, $data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Status updated successfully'
        );

        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
    }

    public function delete($id = null)
    {

        // $sql = 'SELECT * FROM `support_ticket` WHERE office_id = ?';
        // $db = db_connect();
        // $check = $db->query($sql, [$id]);

        $ticket = new SupportTicket();
        $check = $ticket->select('ticket_status_id')->where('ticket_status_id', $id)->countAllResults();

        if($check > 0) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => 'Status has an active related record'
            );
    
            return $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)->setJSON($response);
        } else {
            $status = new TicketStatus();
            if ($status->delete($id)) {
                $response = array(
                    'status' => 'success',
                    'error' => false,
                    'messages' => 'Status deleted successfully'
                );
    
                return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
            } else {
                $response = array(
                    'status' => 'error',
                    'error' => true,
                    'messages' => 'Status not found'
                );
                return $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->setJSON($response);
            }
        }
    }
}
