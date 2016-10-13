<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Sentinel_Filesystem extends Controller
{

    public $sentinel;
    public $result;

    public function before()
    {
        parent::before();
        if ($this->request->action() != 'index')
        {
            $this->sentinel = Sentinel::instance('Filesystem');
        }
    }

    public function action_index()
    {
        $view = View::factory('sentinel/filesystem');
        $this->response->body($view);
    }

    public function action_register()
    {
       $this->sentinel->register_files();
       $this->result = $this->sentinel->registered();
    }

    public function action_modified()
    {
        $this->sentinel->find_modified();
        $this->result = $this->sentinel->modified();
    }

    public function action_unregistered()
    {
        $this->sentinel->find_unregistered();
        $this->result = $this->sentinel->unregistered();
    }

    public function action_deleted()
    {
        $this->sentinel->find_deleted();
        $this->result = $this->sentinel->deleted();
    }

    public function action_updateone()
    {
        $id = $this->request->post('id');
        $result = $this->sentinel->update_checksum($id);
        $this->result = array('result' => $result);
    }

    public function action_backup()
    {
        $result = $this->sentinel->backup();
        $this->result = array('result' => $result);
    }

    public function after()
    {
        if ($this->request->action() != 'index')
        {
            $this->response->headers('Content-Type', 'application/json');
            $this->response->body(json_encode($this->result));
        }
        parent::after();
    }

}
