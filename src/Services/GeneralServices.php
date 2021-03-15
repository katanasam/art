<?php
/**
 * Created by PhpStorm.
 * User: cheik
 * Date: 04/02/2021
 * Time: 19:43
 */

namespace App\Services;

use App\Entity\Post;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class GeneralServices
 * @package App\Services
 */
class GeneralServices
{

    /**
     * Entity manager
     */
    protected $entityManager;


    /**
     * @var ContainerInterface
     */
    protected $container;


    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var ManagerRegistry
     */
    public $managerRegistry;

    /**
     * GeneralServices constructor.
     * @param NormalizerInterface $normalizer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        NormalizerInterface $normalizer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        ManagerRegistry $managerRegistry)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;

        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @param Request $request
     * @param string $class
     * @return mixed
     */
    protected function getSerializer(Request $request,string $class,$format = 'json', $options = [] ){

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serial =  new Serializer($normalizers, $encoders);

        return $object_deserialize = $serial->deserialize($request->getContent(),$class,$format,$options);
    }

    /**
     * @param $errors
     * @return JsonResponse
     */
    public function countAllErrors($errors){
        if(count($errors) > 0){

            // envoie des erreur en json
            return $this->json( $errors,400);
        }
    }

    /**
     * persist et fluch un objet en base de données
     * @param  $object
     */
    public  function PersistAndFlush($object){
        //--- register
        $this->entityManager->persist($object);
        $this->entityManager->flush();

    }

    /**
     * persist et fluch un objet en base de données
     * @param  $object
     */
    public  function RemoveAndFlush($object){
        //--- register
        $this->entityManager->remove($object);
        $this->entityManager->flush();

    }

    /**
     * @param ContainerInterface $container
     * @return null|ContainerInterface
     */
    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        $previous = $this->container;
        $this->container = $container;

        return $previous;
    }

    /**
     * @param $data
     * @param int $status
     * @param array $headers
     * @param array $context
     * @return JsonResponse
     */
    protected function json($data, int $status = 200, array $headers = [], array $context = []): jsonResponse
    {
        if ($this->container->has('serializer')) {
            $json = $this->container->get('serializer')->serialize($data, 'json', array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ], $context));

            return new JsonResponse($json, $status, $headers, true);
        }

        return new JsonResponse($data, $status, $headers);
    }

    /**
     * @param $type
     * @param $content_id
     * @return null|object|string
     */
    public function defineTypeContent($type, $content_id){

        $content ='';

        switch ($type){
            case 'post':
                $content = $this->managerRegistry->getRepository(Post::class )->find($content_id);


            default;
        }

        return $content;
    }


}