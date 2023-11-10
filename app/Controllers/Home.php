<?php

namespace App\Controllers;

use CodeIgniter\Database\RawSql;

class Home extends BaseController
{
    public function index(): string
    {   

        // $db = \Config\Database::connect();
        // $builder = $db->table('authors');

        // $query = $builder->get();
        // $query = $builder->get(10,20);
        // $query = $builder->getWhere(['id' => 2]);
        // $query = $builder->select('id, first_name, last_name, email')->getWhere(['id' => 2]); 

        // $sql = "SELECT * FROM authors WHERE id = 2";
        // $builder->select(new RawSql($sql));
        // $query = $builder->get();

        // $sql = $builder->getCompiledSelect();


        // $query = $builder->selectMax('birthdate')->get();
        // $query = $builder->selectMin('birthdate')->get();
        // $query = $builder->selectAvg('birthdate')->get();
        // $query = $builder->selectSum('birthdate')->get();
        // $query = $builder->selectCount('id')->get(); 

        // $builder->join('posts', 'posts.author_id = authors.id');
        // $query = $builder->get();

        // $builder->where('last_name', "Waters");
        // $builder->where('first_name', "Kirstin");

        // $builder->where('id', 2);
        // $builder->orWhere('id', 3);
        // $builder->whereIn('id', [1,2,3]);
        // $builder->whereNotIn('id', [1,2,3]);
        // $builder->like('first_name', 'Kir');
        // $builder->orLike('last_name', 'Wat');

        // $builder->orderBy('last_name', 'ASC');
        // $builder->orderBy('last_name', 'DESC');
        // $builder->groupBy('last_name');

        // $data = [
        //     'first_name' => 'RUFINO JOHN',
        //     'last_name' => 'AGUILAR',
        //     'email' => 'aguilar@test.com',
        //     'birthdate' => '1980-01-01',
        //     "added" => date('Y-m-d H:i:s')
        // ];

        // $builder->insert($data);

        // $data = [
        //     'first_name' => 'RUFINO',
        //     'last_name' => 'AGUILAR',
        //     'email' => 'aguilar@test.com',
        //     'birthdate' => '1980-01-01',
        //     "added" => date('Y-m-d H:i:s')
        // ];
        // $builder->where('id',101);
        // $builder->update($data);

        // $builder->where('id', 101);
        // $builder->delete();


        // $builder->where('last_name', "AGUILAR");

        // $query = $builder->get();

        // $json = new \stdClass();
        // // $json->sql = $sql;
        // $json->result = $query->getResult();

        // echo json_encode($json);

        return view('welcome_message');
    }
}
