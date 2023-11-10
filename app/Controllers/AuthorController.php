<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\Response;

class AuthorController extends BaseController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        return view('authors/index');
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $author = new \App\Models\Author();
        $data = $author->find($id);
        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($data);
    }

    public function list()
    {
        $postData = $this->request->getPost();

        $response = array();

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value'];
        $sortby = $postData['order'][0]['column']; // Column index
        $sortdir = $postData['order'][0]['dir']; // asc or desc
        $sortcolumn = $postData['columns'][$sortby]['data']; // Column name

        $author = new \App\Models\Author();
        $totalRecords = $author->select('id')->countAllResults();

        $totalRecordwithFilter = $author->select('id')
            ->orLike('last_name', $searchValue)
            ->orLike('first_name', $searchValue)
            ->orLike('email', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();

        $records = $author->select('*')
            ->orLike('last_name', $searchValue)
            ->orLike('first_name', $searchValue)
            ->orLike('email', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);


        $data = array();

        foreach ($records as $record) {
            $data[] = array(
                "id" => $record['id'],
                "last_name" => $record['last_name'],
                "first_name" => $record['first_name'],
                "email" => $record['email'],
                "birthdate" => $record['birthdate']
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

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {

        $author = new \App\Models\Author();
        $data = $this->request->getJSON();

        if (!$author->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $author->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }

        $author->insert($data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Author added successfully'
        );

        return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $author = new \App\Models\Author();
        $data = $this->request->getJSON();
        unset($data->id);


        if (!$author->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $author->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_NOT_MODIFIED)->setJSON($response);
        }

        $author->update($id, $data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Author updated successfully'
        );

        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $author = new \App\Models\Author();

        if ($author->delete($id)) {
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Author deleted successfully'
            );

            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }

        $response = array(
            'status' => 'error',
            'error' => true,
            'messages' => 'Author not found'
        );

        return $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->setJSON($response);
    }
}
