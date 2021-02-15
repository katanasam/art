<?php
/**
 * Created by PhpStorm.
 * User: cheik
 * Date: 04/02/2021
 * Time: 19:43
 */

namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use phpDocumentor\Reflection\Types\Object_;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
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
     * GeneralServices constructor.
     * @param NormalizerInterface $normalizer
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        NormalizerInterface $normalizer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @param string $class
     * @return mixed
     */
    protected function getSerializer(Request $request,string $class){

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serial =  new Serializer($normalizers, $encoders);

        return $object_deserialize = $serial->deserialize($request->getContent(),$class,'json');
    }


    /**
     * @param Object $object
     * @return bool|JsonResponse
     */
    protected function getDataValidate(Object $object){
        //--- validation
        dump('dump 1',$object);

        $errors = $this->validator->validate($object);
        if(count($errors) > 0){

            // envoie des erreur en json
            return $this->json( $errors,400);
        }
        else {

            return true;

        }
    }

    /**
     * @internal
     * @required
     */
    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        $previous = $this->container;
        $this->container = $container;

        return $previous;
    }

    /**
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
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



}