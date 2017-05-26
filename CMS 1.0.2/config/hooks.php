<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'] = array(
    'class' => 'InitializeLoader',
    'function' => 'initialize',
    'filename' => 'InitializeLoader.php',
    'filepath' => 'hooks'
);