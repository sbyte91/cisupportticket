<?php

namespace App\Models;

use CodeIgniter\Model;

class SupportTicket extends Model
{
    protected $table            = 'support_ticket';
    protected $primaryKey       = 'support_ticket_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'support_ticket_id','ticket_num','requested_by','office_id',
        'support_condition_id','description','acted_by','ticket_status_id'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'requested_by' => 'required|is_natural_no_zero',
        'office_id' => 'required|is_natural_no_zero',
        'support_condition_id' => 'required|is_natural_no_zero',
        'description' => 'required|min_length[3]|max_length[400]',
        'ticket_status_id' => 'is_natural_no_zero',
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
