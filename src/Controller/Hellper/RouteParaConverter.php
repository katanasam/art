<?php
/**
 * Created by PhpStorm.
 * User: cheik
 * Date: 08/03/2021
 * Time: 22:20
 */

namespace App\Controller\Hellper;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class RouteParaConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        // TODO: Implement apply() method.
    }

    public function supports(ParamConverter $configuration)
    {
        // TODO: Implement supports() method.
    }


}