<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('portal');
	}
	
	public function tele(){
	    $telegram_id = '494207456';
	    $psn         = 'hello';
	    
	    send_telegram($telegram_id, $psn);
	}
	
	public function wa(){
		
		$this->load->helper('send');
	    $nomor  = '6283851378225';
	    $psn    = 'hello';
	    
	    send_whatsapp($nomor, $psn,'','');
	}
}
