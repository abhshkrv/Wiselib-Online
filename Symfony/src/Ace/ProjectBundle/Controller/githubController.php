<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Abhshkrv
 * Date: 5/19/13
 * Time: 11:27 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Ace\ProjectBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Ace\ProjectBundle\Entity\Project as Project;
use Doctrine\ORM\EntityManager;
use Ace\ProjectBundle\Controller\MongoFilesController;


class GithubController {

    public function cloneprojectAction($user_id, $project_url)
    {
        $retval;
        $response = parent::cloneprojectAction($user_id, $project_url)>getContent();
        $response=json_decode($response, true);

        $gitrepo_details=""; // use Github API GET /repos/:owner/:repo , then json_decode()
        $project_name="";
        $project_details = null;

        if(valid_project($gitrepo_details))
        {   //$project_name = $github_details["name"];
            //..rest of project details
        }
        else
        {
            $retval = $response;
            return new Response(json_encode($retval));
        }

        if($response["success"])
        {
            $response2 = $this->createFileAction($response["id"], $project_name, $project_details)->getContent();
            $response2=json_decode($response2, true);
            if($response2["success"])
            {
                $retval = array("success" => true, "id" => $response["id"]);
            }
            else
                $retval = $response2;
        }
        else
            $retval = $response;

        return new Response(json_encode($retval));
    }
    public function forkprojectAction($user_id, $github_data)
    {
        $retval;
        $response = parent::forkprojectAction($user_id, $github_data)->getContent();
        $response=json_decode($response, true);
        $project_name="";
        $project_details = null;
        if($response["success"]&&valid_user($github_data))
        {
            //Create a fork of wiselib after authenticating(github) user
            // API : POST /repos/:owner/:repo/forks

            //if success
            //$retval = array("success" => true);
            //return new Response(json_encode($retval));
        }

        $retval = $response;
        return new Response(json_encode($retval));

    }
}

