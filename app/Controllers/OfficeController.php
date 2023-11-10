<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Office;
use App\Models\SupportTicket;
use CodeIgniter\HTTP\Response;

class OfficeController extends BaseController
{
    public function index()
    {
        return view('office/index');
    }

    public function show($id = null)
    {
        $office = new Office();
        $data = $office->find($id);

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

        $office = new Office();
        $totalRecords = $office->select('office_id')->countAllResults();

        $totalRecordwithFilter = $office->select('office_id')
            ->orLike('office_name', $searchValue)
            ->orLike('office_code', $searchValue)
            ->orLike('description', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();

        $records = $office->select('*')
            ->orLike('office_name', $searchValue)
            ->orLike('office_code', $searchValue)
            ->orLike('description', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);


        $data = array();

        foreach ($records as $record) {
            $data[] = array(
                "office_id" => $record['office_id'],
                "office_name" => $record['office_name'],
                "office_code" => $record['office_code'],
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
        $office = new Office();
        $data = $this->request->getJSON();

        if (!$office->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $office->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }

        $office->insert($data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Office was successfully added.'
        );

        return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
    }

    public function update($id = null)
    {
        $office = new Office();
        $data = $this->request->getJSON();
        unset($data->id);


        if (!$office->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $office->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_NOT_MODIFIED)->setJSON($response);
        }

        $office->update($id, $data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Office updated successfully'
        );

        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
    }

    public function delete($id = null)
    {
        // $sql = 'SELECT * FROM `support_ticket` WHERE office_id = ?';
        // $db = db_connect();
        // $check = $db->query($sql, [$id]);

        $ticket = new SupportTicket();
        $check = $ticket->select('office_id')->where('office_id', $id)->countAllResults();

        if($check > 0) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => 'Office has an active related record'
            );
            return $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)->setJSON($response);
        } else {
            $office = new Office();
            if ($office->delete($id)) {
                $response = array(
                    'status' => 'success',
                    'error' => false,
                    'messages' => 'Office deleted successfully'
                );
                return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
            } else {
                $response = array(
                    'status' => 'error',
                    'error' => true,
                    'messages' => 'Office not found'
                );
                return $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->setJSON($response);
            }
        }
    }

}
