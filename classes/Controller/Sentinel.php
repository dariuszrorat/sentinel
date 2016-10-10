<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Sentinel extends Controller
{

    public function action_index()
    {
        $view = View::factory('sentinel');
        $this->response->body($view);
    }    
    
    public function action_update()
    {
        $sentinel = Sentinel::instance();
        $sentinel->update();
        $checksums = $sentinel->checksums();

        $this->response->headers('Content-Type', 'application/json');
        $this->response->body(json_encode($checksums));
    }
    
    public function action_updateone()
    {
        $id = $this->request->post('id');
        $sentinel = Sentinel::instance();
        $result = $sentinel->update_checksum($id);        
        $output = array('result' => $result); 
        $this->response->headers('Content-Type', 'application/json');
        $this->response->body(json_encode($output));        
    }
    
    public function action_backup()
    {
        $result = array('result' => 'OK');
        $this->response->headers('Content-Type', 'application/json');
        $this->response->body(json_encode($result));        
    }
    
    public function action_check()
    {
        $sentinel = Sentinel::instance();
        $sentinel->check();
        $changes = $sentinel->changes();

        $this->response->headers('Content-Type', 'application/json');
        $this->response->body(json_encode($changes));
    }
    
}
