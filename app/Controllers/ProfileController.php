<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Profile;
use App\Models\Users;
use CodeIgniter\HTTP\Response;

class ProfileController extends BaseController
{
    public function index()
    {
        $user = new Users();
        $users = $user->select('*')->where('active',1)->findAll();
        return view('profiles/index',['users'=>$users]);
    }

    public function show($id = null)
    {
        $prof = new Profile();
        $data = $prof->find($id);

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

        $prof = new Profile();
        $totalRecords = $prof->select('profile_id')->countAllResults();

        $totalRecordwithFilter = $prof->select('profile_id')
            ->orLike('first_name', $searchValue)
            ->orLike('middle_name', $searchValue)
            ->orLike('last_name', $searchValue)
            ->orLike('birth_date', $searchValue)
            ->orLike('gender_id', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();

        $records = $prof->select('*')
            ->orLike('first_name', $searchValue)
            ->orLike('middle_name', $searchValue)
            ->orLike('last_name', $searchValue)
            ->orLike('birth_date', $searchValue)
            ->orLike('gender_id', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);


        $data = array();

        foreach ($records as $record) {
            $user_id = $record['user_id'];
            $user_model = new Users();
            $user_det = $user_model->select('username,secret')->join('auth_identities', '`auth_identities`.`user_id` = `users`.`id`')->where('`users`.id', $user_id)->findAll();
            $data[] = array(
                "profile_id" => $record['profile_id'],
                "user_id" => $record['user_id'],
                "user_name" => $user_det[0]['username'],
                "email" => $user_det[0]['secret'],
                "first_name" => $record['first_name'],
                "middle_name" => $record['middle_name'],
                "last_name" => $record['last_name'],
                "birth_date" => date("F d, Y", strtotime($record['birth_date'])),
                "gender" => $record['gender_id'] == 1 ? 'Male' : 'Female',
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
        $profile = new Profile();
        $request = $this->request->getJSON();
        $check = $profile->select('user_id')->where('user_id',$request->user_id)->countAllResults();

        if($check > 0){
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => 'Profile for this user is already existing.'
            );
    
            return $this->response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)->setJSON($response);
        } else {
            $data = [
                'user_id' => $request->user_id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'birth_date' => date("Y-m-d", strtotime($request->birth_date)),
                'gender_id'=> $request->gender_id,
            ];
            if (!$profile->validate($data)) {
                $response = array(
                    'status' => 'error',
                    'error' => true,
                    'messages' => $profile->errors()
                );
    
                return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
            }
    
            $profile->insert($data);
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => $profile->errors() //'Ticket was successfully added.'
            );
    
            return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
        }
    }

    public function update($id = null)
    {
        $profile = new Profile();
        $request = $this->request->getJSON();
        unset($request->id);

        $user = $profile->select('user_id')->where('profile_id',$id)->findAll();
        $cur_user = $user[0]['user_id'];

        $data = [
            'user_id' => $cur_user,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'birth_date' => date("Y-m-d", strtotime($request->birth_date)),
            'gender_id'=> $request->gender_id,
        ];
        if (!$profile->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $profile->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_NOT_MODIFIED)->setJSON($response);
        }

        $profile->update($id, $data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Profile updated successfully'
        );

        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
    }

    public function delete($id = null)
    {
        return "Profile deletion not allowed!";
        // $profile = new Profile();
        // if ($profile->delete($id)) {
        //     $response = array(
        //         'status' => 'success',
        //         'error' => false,
        //         'messages' => 'Profile deleted successfully'
        //     );

        //     return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        // } else {
        //     $response = array(
        //         'status' => 'error',
        //         'error' => true,
        //         'messages' => 'Record not found'
        //     );
        //     return $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->setJSON($response);
        // }
    }
}
