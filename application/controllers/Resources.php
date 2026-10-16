<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resources extends CI_Controller {

    private $categories = array(
        'business-excellence' => array(
            'folder' => 'Business Excellence',
            'title'  => 'Business Excellence',
            'desc'   => 'Frameworks, audit forms, and operational best practices for organizational and business mastery.',
            'icon'   => 'ri-line-chart-line',
            'badge'  => 'Management'
        ),
        'career-guidance' => array(
            'folder' => 'Career Guidance',
            'title'  => 'Career Guidance',
            'desc'   => 'Actionable roadmaps, college transitions, and professional skill-building guidebooks.',
            'icon'   => 'ri-compass-3-line',
            'badge'  => 'Career'
        ),
        'ielts-download' => array(
            'folder' => 'IELTS Download',
            'title'  => 'IELTS Download',
            'desc'   => 'Comprehensive IELTS preparation materials, reading questions, and academic task writing tips.',
            'icon'   => 'ri-book-read-line',
            'badge'  => 'Test Prep'
        ),
        'language-power' => array(
            'folder' => 'Language Power',
            'title'  => 'Language Power',
            'desc'   => 'Fluency builders, conversational questioning, and vocabulary to empower your English communication.',
            'icon'   => 'ri-speak-line',
            'badge'  => 'Communication'
        ),
        'personal-growth' => array(
            'folder' => 'Personal Growth',
            'title'  => 'Personal Growth',
            'desc'   => 'Productivity, time management, interview mastery, and life balance development guides.',
            'icon'   => 'ri-user-star-line',
            'badge'  => 'Mindset'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'download'));
    }

    public function index()
    {
        // Overview page showing all free resource download categories
        $data['categories'] = $this->categories;
        $this->load->view('public_header', array('title' => 'Free Resources & Downloads | AlphaMindz'));
        $this->load->view('resources_overview', $data);
        $this->load->view('public_footer');
    }

    public function category($slug = '')
    {
        if (empty($slug) || !isset($this->categories[$slug])) {
            show_404();
        }

        $catInfo = $this->categories[$slug];
        $folderPath = FCPATH . 'assets/free_resources/' . $catInfo['folder'];

        $pdfList = array();
        if (is_dir($folderPath)) {
            $files = scandir($folderPath);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf') {
                    $filePath = $folderPath . '/' . $file;
                    $size = file_exists($filePath) ? filesize($filePath) : 0;
                    
                    // Human readable title from file name
                    $nameOnly = pathinfo($file, PATHINFO_FILENAME);
                    $cleanTitle = ucwords(str_replace(array('-', '_'), ' ', $nameOnly));
                    $cleanTitle = preg_replace('/\s+/', ' ', trim($cleanTitle));

                    $pdfList[] = array(
                        'filename'  => $file,
                        'title'     => $cleanTitle,
                        'size'      => $this->formatBytes($size),
                        'view_url'  => base_url('assets/free_resources/' . rawurlencode($catInfo['folder']) . '/' . rawurlencode($file)),
                        'dl_url'    => site_url('resources/download/' . $slug . '/' . rawurlencode($file))
                    );
                }
            }
        }

        $data['category'] = $catInfo;
        $data['slug'] = $slug;
        $data['pdfs'] = $pdfList;

        $this->load->view('public_header', array('title' => $catInfo['title'] . ' - Free Resources | AlphaMindz'));
        $this->load->view('resources_category', $data);
        $this->load->view('public_footer');
    }

    public function download($slug = '', $filename = '')
    {
        $filename = rawurldecode($filename);
        if (empty($slug) || !isset($this->categories[$slug]) || empty($filename)) {
            show_404();
        }

        // Prevent directory traversal
        $filename = basename($filename);
        $folder = $this->categories[$slug]['folder'];
        $filePath = FCPATH . 'assets/free_resources/' . $folder . '/' . $filename;

        if (!file_exists($filePath) || strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) !== 'pdf') {
            show_404();
        }

        force_download($filename, file_get_contents($filePath));
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
