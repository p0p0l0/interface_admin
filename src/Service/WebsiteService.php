<?php

namespace App\Service;

use App\Entity\Website;

class WebsiteService
{
    // fonction permettant d'installer le script .sh créant le dossier et le site web pour le client
    //changer ip, username, password en passant par $website->getType()->get...()
    public function createWebsite(Website $website){
        
        $command ="sudo chmod +x {$website->getType()->getPath()}{$website->getType()->getScriptCreate()} && {$website->getType()->getPath()}{$website->getType()->getScriptCreate()} {$website->getType()->getPath()} {$website->getServerName()} {$website->getNameFolder()}";            
        $cnx = ssh2_connect("147.123.123.123",22);
        ssh2_auth_password($cnx,"interface","password");
        $stream = ssh2_exec($cnx, $command);
        stream_set_blocking($stream,true);
        
    }

    public function deleteWebsite(Website $website){

        $command = "sudo chmod +x {$website->getType()->getPath()}{$website->getType()->getScriptDelete()} && {$website->getType()->getPath()}{$website->getType()->getScriptDelete()} {$website->getType()->getPath()} {$website->getNameFolder()}";
        $cnx = ssh2_connect("147.123.123.123",22);
        ssh2_auth_password($cnx,"interface","password");
        $stream = ssh2_exec($cnx, $command);
        stream_set_blocking($stream, true);
        
    }

    public function updateWebsite(Website $website, $nameFolderBefore){

        $command = "sudo chmod +x {$website->getType()->getPath()}{$website->getType()->getScriptUpdate()} && {$website->getType()->getPath()}{$website->getType()->getScriptUpdate()} {$website->getType()->getPath()} {$nameFolderBefore}  {$website->getServerName()} {$website->getNameFolder()} ";
        $cnx = ssh2_connect("147.123.123.123",22);
        ssh2_auth_password($cnx,"interface","password");
        $stream = ssh2_exec($cnx, $command);
        stream_set_blocking($stream, true);
        
    }

    public function majWebsite(Website $website){

        $command = "sudo chmod +x {$website->getType()->getPath()}{$website->getType()->getScriptMaj()} && {$website->getType()->getPath()}{$website->getType()->getScriptMaj()} {$website->getType()->getPath()} {$website->getNameFolder()}";
        $cnx = ssh2_connect("147.123.123.123",22);
        ssh2_auth_password($cnx,"interface","password");
        $stream = ssh2_exec($cnx, $command);
        stream_set_blocking($stream, true);
        
    }

}