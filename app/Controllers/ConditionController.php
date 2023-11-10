<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupportCondition;
use App\Models\SupportTicket;
use CodeIgniter\HTTP\Response;

class ConditionController extends BaseController
{
    public function index()
    {
        return view('condition/index');
    }

    public function show($id = null)
    {
        $condition = new SupportCondition();
        $data = $condition->find($id);

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

        $condition = new SupportCondition();
        $totalRecords = $condition->select('support_condition_id')->countAllResults();

        $totalRecordwithFilter = $condition->select('support_condition_id')
            ->orLike('condition', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();

        $records = $condition->select('*')
            ->orLike('condition', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);


        $data = array();

        foreach ($records as $record) {
            $data[] = array(
                "support_condition_id" => $record['support_condition_id'],
                "condition" => $record['condition'],
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
        $condition = new SupportCondition();
        $data = $this->request->getJSON();

        if (!$condition->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $condition->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }

        $condition->insert($data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Condition was successfully added.'
        );

        return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
    }

    public function update($id = null)
    {
        $condition = new SupportCondition();
        $data = $this->request->getJSON();
        unset($data->id);
        
        if (!$condition->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $condition->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_NOT_MODIFIED)->setJSON($response);
        }

        $condition->update($id, $data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Condition updated successfully'
        );

        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
    }

    public function delete($id = null)
    {
        // $sql = 'SELECT * FROM `support_ticket` WHERE office_id = ?';
        // $db = db_connect();
        // $check = $db->query($sql, [$id]);

        $ticket = new SupportTicket();
        $check = $ticket->select('support_condition_id')->where('support_condition_id', $id)->countAllResults();

        if($check > 0) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => 'Condition has an active related record'
            );
    
            return $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)->setJSON($response);
        } else {
            $condition = new SupportCondition();
            if ($condition->delete($id)) {
                $response = array(
                    'status' => 'success',
                    'error' => false,
                    'messages' => 'Condition deleted successfully'
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
