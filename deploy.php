<?php
namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:RedCiudadana/IndicesYMedicion.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('medicion.redciudadana.org')
    ->set('remote_user', 'redciudadana')
    ->set('deploy_path', '/srv/web-apps/IndicesYMedicion');

// Hooks

after('deploy:failed', 'deploy:unlock');
