<?php

namespace Calendar51\AppBundle\Controller;

use Calendar51\Domain\Exception\InvalidDomainData;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class EventController
 *
 * @package Calendar51\AppBundle\Controller
 */
class EventController extends Controller
{
    /**
     * Add Event Action
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        try {
            $params = $request->request->all();
            $this->get('calendar51.validator')->validateNewEventData($params);

            $rep = $this->get('event.repository');
            $con = $this->get('calendar51.pdo')->getCon();

            $id = $rep->add($con, $params);
        } catch (InvalidDomainData $e) {
            return $this->clientFailure($e->getMessage());
        } catch (\PDOException $e) {
            return $this->serverFailure($e->getMessage());
        } catch (\Exception $e) {
            return $this->serverFailure($e->getMessage());
        }

        return $this->success(array('id' => $id));
    }

    /**
     * Update Event Action.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request)
    {
        try {
            $params = $request->request->all();
            $this->get('calendar51.validator')->validateUpdateEventData($params);

            $rep = $this->get('event.repository');
            $con = $this->get('calendar51.pdo')->getCon();

            $rep->update($con, $params);
        } catch (InvalidDomainData $e) {
            return $this->clientFailure($e->getMessage());
        } catch (\PDOException $e) {
            return $this->serverFailure($e->getMessage());
        } catch (\Exception $e) {
            return $this->serverFailure($e->getMessage());
        }

        return $this->success(
            sprintf('Event `%s` successfully updated.', $params['id'])
        );
    }

    /**
     * Delete Event Action.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        try {
            $id = $request->request->get('id');
            $this->get('calendar51.validator')->validateEventId($id);

            $rep = $this->get('event.repository');
            $con = $this->get('calendar51.pdo')->getCon();

            $rep->delete($con, $id);
        } catch (InvalidDomainData $e) {
            return $this->clientFailure($e->getMessage());
        } catch (\PDOException $e) {
            return $this->serverFailure($e->getMessage());
        } catch (\Exception $e) {
            return $this->serverFailure($e->getMessage());
        }

        return $this->success(
            sprintf('Event `%s` successfully deleted.', $id)
        );
    }

    /**
     * Get Event Action.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getAction($id)
    {
        try {
            $this->get('calendar51.validator')->validateEventId($id);

            $rep = $this->get('event.repository');
            $con = $this->get('calendar51.pdo')->getCon();

            $event = $rep->findById($con, $id);
        } catch (InvalidDomainData $e) {
            return $this->clientFailure($e->getMessage());
        } catch (\Exception $e) {
            return $this->serverFailure($e->getMessage());
        }

        return $this->success($event);
    }

    /**
     * Get all Events Action.
     *
     * @return JsonResponse
     */
    public function allAction()
    {
        try {
            $rep = $this->get('event.repository');
            $con = $this->get('calendar51.pdo')->getCon();

            $events = $rep->findAll($con);
        } catch (\Exception $e) {
            return $this->serverFailure($e->getMessage());
        }

        return $this->success($events);
    }

    /**
     * @param string $message
     * @param int    $statusCode
     *
     * @return JsonResponse
     */
    protected function serverFailure($message, $statusCode = Response::HTTP_SERVICE_UNAVAILABLE)
    {
        return new JsonResponse(array('message' => $message), $statusCode);
    }

    /**
     * @param string $message
     * @param int    $statusCode
     *
     * @return JsonResponse
     */
    protected function clientFailure($message, $statusCode = Response::HTTP_BAD_REQUEST)
    {
        return new JsonResponse(array('message' => $message), $statusCode);
    }

    /**
     * @param string|array $data
     * @param int          $statusCode
     *
     * @return JsonResponse
     */
    protected function success($data, $statusCode = Response::HTTP_OK)
    {
        $serializer = new Serializer(
            array(new ObjectNormalizer()),
            array(new JsonEncoder())
        );

        if (is_array($data)) {
            foreach ($data as &$obj) {
                $obj = json_decode($serializer->serialize($obj, 'json'));
            }
        } else if (is_object($data)) {
            $data = json_decode($serializer->serialize($data, 'json'));
        }

        return new JsonResponse(array('data' => $data), $statusCode);
    }
}
