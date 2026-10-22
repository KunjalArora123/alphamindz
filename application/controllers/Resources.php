<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resources extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'download'));
        $this->load->database();
    }

    private function get_active_categories() {
        $this->db->where('is_active', 1);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get('guide_categories');
        $result = array();
        foreach ($query->result() as $row) {
            $result[$row->slug] = array(
                'id'          => $row->id,
                'folder'      => $row->folder_name,
                'title'       => $row->title,
                'desc'        => $row->description,
                'icon'        => !empty($row->icon) ? $row->icon : 'ri-file-pdf-line',
                'badge'       => !empty($row->badge) ? $row->badge : 'Guide',
                'slug'        => $row->slug
            );
        }
        return $result;
    }

    public function index()
    {
        $categories = $this->get_active_categories();
        
        // Count active PDFs per category from DB
        foreach ($categories as $slug => &$cat) {
            $this->db->where('category_id', $cat['id']);
            $this->db->where('is_active', 1);
            $cat['pdf_count'] = $this->db->count_all_results('guide_files');
        }

        $data['categories'] = $categories;
        $this->load->view('public_header', array('title' => 'Free Resources & Downloads | AlphaMindz'));
        $this->load->view('resources_overview', $data);
        $this->load->view('public_footer');
    }

    public function category($slug = '')
    {
        if (empty($slug)) {
            show_404();
        }

        $categories = $this->get_active_categories();
        if (!isset($categories[$slug])) {
            show_404();
        }

        $catInfo = $categories[$slug];

        // Fetch active PDFs for this category from database
        $this->db->where('category_id', $catInfo['id']);
        $this->db->where('is_active', 1);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'ASC');
        $files = $this->db->get('guide_files')->result();

        $pdfList = array();
        foreach ($files as $f) {
            $fullPath = FCPATH . $f->file_path;
            $size = (!empty($f->file_size)) ? $f->file_size : (file_exists($fullPath) ? $this->formatBytes(filesize($fullPath)) : 'PDF');

            $pdfList[] = array(
                'id'        => $f->id,
                'filename'  => $f->filename,
                'title'     => $f->title,
                'size'      => $size,
                'view_url'  => base_url($f->file_path),
                'dl_url'    => site_url('resources/download/' . $slug . '/' . $f->id)
            );
        }

        $data['category'] = $catInfo;
        $data['slug']     = $slug;
        $data['pdfs']     = $pdfList;

        $this->load->view('public_header', array('title' => $catInfo['title'] . ' - Free Resources | AlphaMindz'));
        $this->load->view('resources_category', $data);
        $this->load->view('public_footer');
    }

    public function download($slug = '', $file_id = '')
    {
        if (empty($slug) || empty($file_id)) {
            show_404();
        }

        $categories = $this->get_active_categories();
        if (!isset($categories[$slug])) {
            show_404();
        }

        $catInfo = $categories[$slug];

        // If file_id is numeric, lookup in DB
        if (is_numeric($file_id)) {
            $file_row = $this->db->get_where('guide_files', array('id' => (int)$file_id, 'category_id' => $catInfo['id']))->row();
            if ($file_row && file_exists(FCPATH . $file_row->file_path)) {
                force_download($file_row->filename, file_get_contents(FCPATH . $file_row->file_path));
                return;
            }
        }

        // Backward compatibility for raw filename
        $filename = basename(rawurldecode($file_id));
        $filePath = FCPATH . 'assets/free_resources/' . $catInfo['folder'] . '/' . $filename;
        if (file_exists($filePath) && strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) === 'pdf') {
            force_download($filename, file_get_contents($filePath));
            return;
        }

        show_404();
    }

    private function formatBytes($bytes, $precision = 1) {
        $units = array('B', 'KB', 'MB', 'GB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
