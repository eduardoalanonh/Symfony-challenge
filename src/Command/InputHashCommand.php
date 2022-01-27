<?php


namespace App\Command;

use App\DTO\ResponseObjectDTO;
use App\Repository\HashRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;



class InputHashCommand extends Command
{
    private HttpClientInterface $client;
    private HashRepository $repository;
    private string $baseUrl;

    public function __construct(HttpClientInterface $client, HashRepository $repository, string $baseUrl)
    {
        $this->client = $client;
        $this->repository = $repository;
        $this->baseUrl = $baseUrl;

        parent::__construct();
    }

    protected static $defaultName = 'symfony:test';

    protected function configure(): void
    {
        $this->addArgument('string', InputArgument::REQUIRED);
        $this->addArgument('requests', InputArgument::REQUIRED);
    }


    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $string = $input->getArgument('string');
        $requests = $input->getArgument('requests');

        if (!ctype_digit($requests)) {
            $output->writeln([
                '<error>the second argument needs to be a number, please try again</error>'
            ]);

            return Command::INVALID;
        }

        for ($block = 1; $block <= $requests; $block++) {

            $response = $this->client->request('GET', $this->baseUrl . "/make-hash/{$string}");

            while ($response->getStatusCode() === Response::HTTP_TOO_MANY_REQUESTS) {

                $retry = $this->returnRetryTime($response->getHeaders(false));
                sleep($retry - time());
                $response = $this->client->request('GET', $this->baseUrl . "/make-hash/{$string}");

            }
            $data = json_decode($response->getContent(false));
            $hashDto = new ResponseObjectDTO(count: $data->attempts, randomKey: $data->key, randomHash: $data->hash);
            $hashPersisted = $this->repository->addHash($hashDto, $block, $string);

            $output->writeln([
                '<info> batch:</info> ' . $hashPersisted->getTimeStamp()->format('Y/m/d h:i:s') .
                ' | <info>block: </info>' . $hashPersisted->getBlockNumber() .
                ' | <info>input:</info>' . $hashPersisted->getInputString() .
                ' | <info>key:</info>' . $hashPersisted->getKeyFound() .
                ' | <info>hash:</info>' . $hashPersisted->getHash() .
                ' | <info>attempts:</info>' . $hashPersisted->getAttempts()
            ]);
            $output->writeln(['<comment>==========================================================================================================================================</comment>',
            ]);

            $string = $hashDto->getRandomHash();

        }

        return Command::SUCCESS;

    }

    /**
     * @param array $header
     * @return string
     */
    private function returnRetryTime(array $header) : string
    {
        return $header['x-ratelimit-retry-after']['0'];
    }

}
