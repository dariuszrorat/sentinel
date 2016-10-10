<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Sentinel extends Controller
{

    public function action_index()
    {
        $view = View::factory('sentinel');
        $this->response->body($view);
    }    
    
    public function action_register()
    {
        $sentinel = Sentinel::instance();
        $sentinel->register_files();
        $registered = $sentinel->registered();

        $this->response->headers('Content-Type', 'application/json');
        $this->response->body(json_encode($registered));
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
    
    public function action_modified()
    {
        $sentinel = Sentinel::instance();
        $sentinel->find_modified();
        $modified = $sentinel->modified();

        $this->response->headers('Content-Type', 'application/json');
        $this->response->body(json_encode($modified));
    }

    public function action_unregistered()
    {
        $sentinel = Sentinel::instance();
        $sentinel->find_unregistered();
        $unregistered = $sentinel->unregistered();

        $this->response->headers('Content-Type', 'application/json');
        $this->response->body(json_encode($unregistered));
    }

    public function action_deleted()
    {
        $sentinel = Sentinel::instance();
        $sentinel->find_deleted();
        $deleted = $sentinel->deleted();

        $this->response->headers('Content-Type', 'application/json');
        $this->response->body(json_encode($deleted));
    }
    
}
