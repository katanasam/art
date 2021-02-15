<?php
/**
 * Created by PhpStorm.
 * User: cheik
 * Date: 04/02/2021
 * Time: 19:43
 */

namespace App\Services;


use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * Class PostServices
 * @package App\Services
 */
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

    /**
     * PostServices constructor.
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRP = $postRepository;
        $this->em = $entityManager;
    }


    /**
     * @param User $user
     * @return EntityManagerInterface
     */
    public function AllUserPosts(User $user)
    {
        $all_user_posts = $this->postRP->findPostByUser($user->getId());

        return $this->$all_user_posts;
    }
}