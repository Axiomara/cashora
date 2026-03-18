<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property CI_Pagination $pagination
 */

class Audit_log extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('pagination');
    }

    public function index()
    {

        $q    = trim((string)$this->input->get('q', TRUE));
        $from = trim((string)$this->input->get('from', TRUE));
        $to   = trim((string)$this->input->get('to', TRUE));
        $pageParam = $this->input->get('page', TRUE);

        $perPage = 20;
        $this->db->from('audit_log');

        if ($q !== '') {
            $this->db->group_start();
            $this->db->like('aksi', $q);
            $this->db->or_like('tabel', $q);
            $this->db->or_like('keterangan', $q);
            $this->db->group_end();
        }

        if ($from !== '') {
            $this->db->where('DATE(created_at) >=', $from);
        }

        if ($to !== '') {
            $this->db->where('DATE(created_at) <=', $to);
        }

        $totalRows = $this->db->count_all_results();


        // =========================
        // PAGINATION CONFIG
        // =========================

        $config['base_url'] = base_url('audit_log');
        $config['total_rows'] = $totalRows;
        $config['per_page'] = $perPage;

        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open']  = '<nav><ul class="pagination justify-content-end mb-0">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['num_tag_open']   = '<li class="page-item">';
        $config['num_tag_close']  = '</li>';

        $config['cur_tag_open']   = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']  = '</a></li>';

        $config['next_tag_open']  = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open']  = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['attributes'] = ['class' => 'page-link'];

        $config['first_link'] = '«';
        $config['last_link']  = '»';
        $config['next_link']  = '›';
        $config['prev_link']  = '‹';

        $this->pagination->initialize($config);


        // =========================
        // PAGE -> OFFSET
        // =========================

        $page = (ctype_digit((string)$pageParam) && (int)$pageParam > 0)
            ? (int)$pageParam : 1;

        $offset = ($page - 1) * $perPage;


        // =========================
        // GET DATA
        // =========================

        $this->db->select('audit_log.*, users.username');
        $this->db->from('audit_log');
        $this->db->join('users', 'users.id = audit_log.user_id', 'left');

        if ($q !== '') {
            $this->db->group_start();
            $this->db->like('aksi', $q);
            $this->db->or_like('tabel', $q);
            $this->db->or_like('keterangan', $q);
            $this->db->group_end();
        }

        if ($from !== '') {
            $this->db->where('DATE(audit_log.created_at) >=', $from);
        }

        if ($to !== '') {
            $this->db->where('DATE(audit_log.created_at) <=', $to);
        }

        $logs = $this->db
            ->order_by('audit_log.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->result();


        // =========================
        // DATA VIEW
        // =========================

        $data = [
            'logs' => $logs,
            'pagination' => $this->pagination->create_links(),
            'q' => $q,
            'from' => $from,
            'to' => $to,
            'totalRows' => $totalRows,
            'page' => $page,
            'offset' => $offset
        ];


        // =========================
        // LOAD VIEW
        // =========================

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/sidebar');
        $this->load->view('audit_log/index', $data);
        $this->load->view('dashboard/footer');
    }
}
