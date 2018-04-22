<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * CONTROLLER TEST
 * TODO : create a PhpUnit test
 *
 * Class AstrobinWsController
 * @package AppBundle\Controller
 */
class AstrobinWsController extends Controller
{

    /**
     * @Route("/astrobin/id/{id}", name="astrobin_image_id")
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinException
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinResponseExceptions
     * @throws \ReflectionException
     */
    public function getImageByIdAction(Request $request, $id)
    {
        $astrobinWS = $this->container->get('astrobin.webservice.getimage');
        $data = $astrobinWS->getImageById($id);

        $response = new Response();
        return $this->render('pages/astrobin.html.twig', ['data' => $data], $response);
    }


    /**
     * @Route("/astrobin/images/{messierId}/{limit}", name="astrobin_image_messier")

     * @param Request $request
     * @param $messierId
     * @param int $limit
     * @return Response
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinException
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinResponseExceptions
     * @throws \ReflectionException
     */
    public function getImageBySubjectAction(Request $request, $messierId, $limit = 1)
    {

        $astrobinWs = $this->container->get('astrobin.webservice.getimage');
        $data = $astrobinWs->getImagesBySubject($messierId, $limit);

        $response = new Response();
        return $this->render('pages/astrobin.html.twig', ['data' => $data], $response);
    }


    /**
     * @Route("/astrobin/user/{username}/{limit}", name="astrobin_image_user")
     * @param Request $request
     * @param string $username
     * @return Response
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinException
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinResponseExceptions
     * @throws \ReflectionException
     */
    public function getImageByUserAction(Request $request, $username, $limit)
    {
        $data = [];

        $astrobinWs = $this->container->get('astrobin.webservice.getimage');
        $data = $astrobinWs->getImagesByUser($username, $limit);

        $response = new Response();
        return $this->render('pages/astrobin.html.twig', ['data' => $data], $response);
    }


    /**
     * @Route("/astrobin/search/{desc}", name="astrobin_search")
     *
     * @param Request $request
     * @param string $desc
     * @return Response
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinException
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinResponseExceptions
     * @throws \ReflectionException
     */
    public function getImagesByDescriptionAction(Request $request, $desc = '')
    {
        $astrobinWs = $this->container->get('astrobin.webservice.getimage');
        $data = $astrobinWs->getImagesByDescription($desc, 5);

        $response = new Response();
        return $this->render('pages/astrobin.html.twig', ['data' => $data], $response);
    }


    /**
     * @Route("/astrobin/today", name="astrobin_image_of_day")
     * @param Request $request
     * @return Response
     * @throws \AppBundle\Astrobin\Exceptions\AstrobinResponseExceptions
     * @throws \AppBundle\Astrobin\Exceptions\astrobinException
     * @throws \ReflectionException
     */
    public function getImageOfTheDay(Request $request)
    {
        $astrobinWs = $this->container->get('astrobin.webservice.gettodayimage');
        $data = $astrobinWs->getTodayImage();

        /** @var Response $response */
        $response = new Response();
        return $this->render('pages/astrobin.html.twig', ['data' => $data], $response);
    }
}
