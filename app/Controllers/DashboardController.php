<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index(): string
    {   
        $author = new \App\Models\Author();
        $post = new \App\Models\Post();

        $data['totalauthors'] = $author->countAllResults();
        $data['totalposts'] = $post->countAllResults();

        $data['authornames'] = $author
        ->select("CONCAT(first_name, ' ', last_name) AS author_name")
        ->orderBy('id')
        ->findAll();

        $data['postsforeachauthors'] = $post->selectCount('author_id')
        ->orderBy('author_id')
        ->groupBy('author_id')->findAll();

        return view('dashboard/index',$data);
    }
}
