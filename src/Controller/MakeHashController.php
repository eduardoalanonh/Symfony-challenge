<?php

namespace App\Controller;

use App\Repository\HashRepository;
use App\Services\MakeHashService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class MakeHashController extends AbstractController


{
    private makeHashService $service;
    private HashRepository $repository;

    public function __construct(MakeHashService $service, HashRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }


    #[Route('/make-hash/{input}', name: 'make_hash', methods: ['GET'])]
    public function index(Request $request, string $input, RateLimiterFactory $anonymousApiLimiter): Response
    {
        try {
            $limiter = $anonymousApiLimiter->create($request->getClientIp());

            $limit = $limiter->consume();

            $headers = [
                'X-RateLimit-Retry-After' => $limit->getRetryAfter()->getTimestamp(),
            ];

            if (!$limit->isAccepted()) {
                throw new TooManyRequestsHttpException();
            }

            $response = $this->service->makeHash($input);

            return $this->json([
                'hash' => $response->getRandomHash(),
                'key' => $response->getRandomKey(),
                'attempts' => $response->getCount()
            ], 200, $headers);
        } catch (TooManyRequestsHttpException) {
            return $this->json(['message' => 'Too many requests'], Response::HTTP_TOO_MANY_REQUESTS, $headers ?? null);
        }
    }

    #[Route('/hash', name: 'get_hash', methods: ['GET'])]
    public function showHashes(Request $request): Response
    {
        try {
            $attempts = $request->query->get('less_attempts');
            $limit = $request->query->getInt('limit');
            $page = $request->query->getInt('page');

            $response = $this->repository->getAllHash(attempts: $attempts, page: $page, limit: $limit);

            return $this->json([
                'data' => $response->getData(),
                'total_items' => $response->getTotalItems(),
                'items_per_page' => $response->getItemsPerPage(),
                'page' => $response->getPage(),
                'limit' => $response->getLimit(),
            ]);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }
}
