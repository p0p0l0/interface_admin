<?php

namespace App\Service;

use App\Entity\Website;

class WebsiteService
{

    public function createWebsite(Website $website){
        
        $command ="sudo chmod +x {$website->getType()->getPath()}{$website->getType()->getScriptCreate()} && {$website->getType()->getPath()}{$website->getType()->getScriptCreate()} {$website->getType()->getPath()} {$website->getServerName()} {$website->getNameFolder()}";            
        $cnx = ssh2_connect("147.135.162.109",22);
        ssh2_auth_password($cnx,"interface","9w4hZ9Ke7D");
        $stream = ssh2_exec($cnx, $command);
        stream_set_blocking($stream,true);
        
    }

    public function deleteWebsite(Website $website){

        $command = "sudo chmod +x {$website->getType()->getPath()}{$website->getType()->getScriptDelete()} && {$website->getType()->getPath()}{$website->getType()->getScriptDelete()} {$website->getType()->getPath()} {$website->getNameFolder()}";
        $cnx = ssh2_connect("147.135.162.109",22);
        ssh2_auth_password($cnx,"interface","9w4hZ9Ke7D");
        $stream = ssh2_exec($cnx, $command);
        stream_set_blocking($stream, true);
        
    }

    public function updateWebsite(Website $website){

        $command = "sudo chmod +x {$website->getType()->getPath()}{$website->getType()->getScriptUpdate()} && {$website->getType()->getPath()}{$website->getType()->getScriptUpdate()} {$website->getType()->getPath()} {$website->getServerName()} {$website->getNameFolder()}";
        $cnx = ssh2_connect("147.135.162.109",22);
        ssh2_auth_password($cnx,"interface","9w4hZ9Ke7D");
        $stream = ssh2_exec($cnx, $command);
        stream_set_blocking($stream, true);
        
    }

    public function majWebsite(Website $website){

        $command = "sudo chmod +x {$website->getType()->getPath()}{$website->getType()->getScriptMaj()} && {$website->getType()->getPath()}{$website->getType()->getScriptMaj()} {$website->getType()->getPath()} {$website->getServerName()} {$website->getNameFolder()}";
        $cnx = ssh2_connect("147.135.162.109",22);
        ssh2_auth_password($cnx,"interface","9w4hZ9Ke7D");
        $stream = ssh2_exec($cnx, $command);
        stream_set_blocking($stream, true);
        
    }
}