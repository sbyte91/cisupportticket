<?php

namespace App\Models;

use CodeIgniter\Model;

class SupportTicketResponse extends Model
{
    protected $table            = 'support_ticket_response';
    protected $primaryKey       = 'support_ticket_response_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['support_ticket_id','remarks','ticket_status_id','acted_by'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'support_ticket_id' => 'required|is_natural_no_zero',
        'remarks' => 'required|min_length[3]|max_length[200]',
        'acted_by' => 'required|is_natural_no_zero',
        'ticket_status_id' => 'required|is_natural_no_zero',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
