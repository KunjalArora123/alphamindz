<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
    }

    public function index() {
        $defaults = array(
            'about_hero_tag'        => 'Our Story',
            'about_hero_title'      => 'About AlphaMindz',
            'about_hero_subtitle'   => 'Empowering minds, inspiring futures, and motivating individuals to reach their highest potential since 2007.',
            'about_team_heading'    => 'Meet Our Team',
            'about_team_subheading' => 'The visionary leaders and dedicated professionals driving our mission forward across our regional hubs.',
            'about_solan_title'     => 'Solan Branch (Himachal Pradesh)',
            'about_solan_address'   => 'First Floor, Satish Complex, Kotla Nala, Solan, Himachal Pradesh 173212',
            'about_solan_map_link'  => 'https://www.google.com/maps/place/First+Floor,+Alpha+Mindz+Solan,+Satish+Complex,+Kotla+Nala,+Solan,+Himachal+Pradesh+173212/data=!4m2!3m1!1s0x390f8138573242bb:0x1e78d5e86a03c187!18m1!1e1',
            'about_goa_title'       => 'Goa Branch (Panaji)',
            'about_goa_address'     => '302, Sai Plaza, opp. Gomantak Press, St. Inez, Altinho, Panaji, Goa 403001',
            'about_goa_map_link'    => 'https://maps.app.goo.gl/orfvmi45fKQES2Mx6'
        );

        foreach ($defaults as $k => $def) {
            $row = $this->db->get_where('system_settings', array('setting_key' => $k))->row();
            $data[$k] = ($row && $row->setting_value !== null && $row->setting_value !== '') ? $row->setting_value : $def;
        }

        $this->db->where('is_active', 1);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'ASC');
        $all_members = $this->db->get('about_team_members')->result();

        $solan_members = array();
        $goa_members   = array();
        if (!empty($all_members)) {
            foreach ($all_members as $m) {
                if ($m->branch_key === 'goa') {
                    $goa_members[] = $m;
                } else {
                    $solan_members[] = $m;
                }
            }
        }

        $data['solan_members'] = $solan_members;
        $data['goa_members']   = $goa_members;

        $this->load->view('about', $data);
    }
}
