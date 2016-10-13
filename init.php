<?php

Route::set('sentinel/filesystem', 'sentinel/filesystem')
    ->defaults(array(
        'directory'  => 'sentinel',
        'controller' => 'filesystem',
        'action'     => 'index',
));

Route::set('sentinel/filesystem/register', 'sentinel/filesystem/register')
    ->defaults(array(
        'directory'  => 'sentinel',
        'controller' => 'filesystem',
        'action'     => 'register',
));

Route::set('sentinel/filesystem/modified', 'sentinel/filesystem/modified')
    ->defaults(array(
        'directory'  => 'sentinel',
        'controller' => 'filesystem',
        'action'     => 'modified',
));

Route::set('sentinel/filesystem/unregistered', 'sentinel/filesystem/unregistered')
    ->defaults(array(
        'directory'  => 'sentinel',
        'controller' => 'filesystem',
        'action'     => 'unregistered',
));

Route::set('sentinel/filesystem/deleted', 'sentinel/filesystem/deleted')
    ->defaults(array(
        'directory'  => 'sentinel',
        'controller' => 'filesystem',
        'action'     => 'deleted',
));

Route::set('sentinel/filesystem/backup', 'sentinel/filesystem/backup')
    ->defaults(array(
        'directory'  => 'sentinel',
        'controller' => 'filesystem',
        'action'     => 'backup',
));

Route::set('sentinel/filesystem/updateone', 'sentinel/filesystem/updateone')
    ->defaults(array(
        'directory'  => 'sentinel',
        'controller' => 'filesystem',
        'action'     => 'updateone',
));


