<?php

namespace AppBundle\Controller;

use Astrobin\Services\GetImage;
use Astrobin\Services\GetTodayImage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * CONTROLLER TEST
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
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     * @throws \ReflectionException
     */
    public function getImageByIdAction(Request $request, $id)
    {
        $astrobinWS = $this->container->get('astrobin.webservice.getimage');
        $data = $astrobinWS->getImageById($id);
        dump($data);

        $response = new Response();
        return $this->render('pages/astrobin.html.twig', ['data' => $data], $response);
    }


    /**
     * @Route("/astrobin/images/{messierId}/{limit}", name="astrobin_image_messier")
     * @param Request $request
     * @param $messierId
     * @param int $limit
     * @return Response
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     * @throws \ReflectionException
     */
    public function getImageBySubjectAction(Request $request, $messierId, $limit = 1)
    {
        /** @var GetImage $astrobinWs */
        $astrobinWs = $this->container->get('astrobin.webservice.getimage');
        $data = $astrobinWs->getImagesBySubject($messierId, $limit);

        $response = new Response();
        return $this->render('pages/astrobin.html.twig', ['data' => $data], $response);
    }


    /**
     * @Route("/astrobin/user/{username}/{limit}", name="astrobin_image_user")
     * @param Request $request
     * @param $username
     * @param $limit
     * @return Response
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     * @throws \ReflectionException
     */
    public function getImageByUserAction(Request $request, $username, $limit)
    {
        $data = [];
        /** @var GetImage $astrobinWs */
        $astrobinWs = $this->container->get('astrobin.webservice.getimage');
        $data = $astrobinWs->getImagesByUser($username, $limit);

        $response = new Response();
        return $this->render('pages/astrobin.html.twig', ['data' => $data], $response);
    }


    /**
     * @Route("/astrobin/search/{desc}", name="astrobin_search")
     * @param Request $request
     * @param string $desc
     * @return Response
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     * @throws \ReflectionException
     */
    public function getImagesByDescriptionAction(Request $request, $desc = '')
    {
        /** @var GetImage $astrobinWs */
        $astrobinWs = $this->container->get('astrobin.webservice.getimage');
        $data = $astrobinWs->getImagesByDescription($desc, 5);

        $response = new Response();
        return $this->render('pages/astrobin.html.twig', ['data' => $data], $response);
    }


    /**
     * @Route("/astrobin/today", name="astrobin_image_of_day")
     * @param Request $request
     * @return Response
     * @throws \Astrobin\Exceptions\WsException
     * @throws \Astrobin\Exceptions\WsResponseException
     * @throws \ReflectionException
     */
    public function getImageOfTheDay(Request $request)
    {
        /** @var GetTodayImage $astrobinWs */
        $astrobinWs = $this->container->get('astrobin.webservice.gettodayimage');
        $data = $astrobinWs->getTodayDayImage();
        dump($data);
        /** @var Response $response */
        $response = new Response();
        return $this->render('pages/astrobin.html.twig', ['data' => $data], $response);
    }
}
