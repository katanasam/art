<?php
/**
 * Created by PhpStorm.
 * User: cheik
 * Date: 04/02/2021
 * Time: 19:43
 */

namespace App\Services;


use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class PostServices
{

    /**
     * post repository
     */
    private $postRP;

    /**
     * Entity manager
     */
    private $em;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRP = $postRepository;
        $this->em = $entityManager;
    }


}