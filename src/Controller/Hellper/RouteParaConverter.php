<?php
/**
 * Created by PhpStorm.
 * User: cheik
 * Date: 08/03/2021
 * Time: 22:20
 */

namespace App\Controller\Hellper;


use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;

class RouteParaConverter implements ParamConverterInterface
{

    public function supports(ParamConverter $configuration)
    {
        // TODO: Implement supports() method.
        return $configuration->getName() === 'type';

    }


    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var ExpressionLanguage
     */
    private $language;

    /**
     * @var array
     */
    private $defaultOptions;

    public function __construct(ManagerRegistry $registry = null, ExpressionLanguage $expressionLanguage = null, array $options = [])
    {
        $this->registry = $registry;
        $this->language = $expressionLanguage;


    }

    /**
     * {@inheritdoc}
     *
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();
        $class = $configuration->getClass();
       dd($name,$class);

        return true;
    }
}