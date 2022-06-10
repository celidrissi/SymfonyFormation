<?php

namespace App\Command;

use App\Entity\Restaurant;
use App\Entity\Address;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function PHPUnit\Framework\matches;

class ImportRestaurantsCommand extends Command
{
    private $httpClient;
    protected static $defaultName = 'app:import:restaurants';
    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('address', InputArgument::OPTIONAL, 'Restaurants nearby this address')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if(!$address = $input->getArgument('address')) {
            $Question = new Question('Where you want to eat ?');
            $Question->setMaxAttempts(3);
            $Question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('You must provide an address');
                }
                return $answer;
            });
            $address = $io->askQuestion($Question);
        }

        $io->title('Importing Restaurants near : ' . $address);
        
        $response = $this->httpClient->request('GET', 'https://maps.googleapis.com/maps/api/place/textsearch/json', [
            'query' => [
                'query' => $address,
                'key' => $_ENV['GOOGLE_API_KEY'],
                'type' => 'restaurant'
            ],
        ]);

        $restaurants = $response->toArray();

        if (count($restaurants['results']) === 0) {
            $io->error('No restaurants found');
            return 1;
        }

        $io->info('Find ' . count($restaurants['results']) . ' restaurants');
        $bar = $io->createProgressBar(count($restaurants['results']));

        $rows = [];
        $badAdrress = 0;
        foreach($restaurants['results'] as $restaurantDATA) {
            $bar->start();
            $matches = [];
            preg_match_all('/(.*),\s(\d+)\s(.*),\s(.*)/xi', $restaurantDATA['formatted_address'], $matches);
            
            if(!$matches[4]){
                $badAdrress++;
                continue;
            }
            
            $address = new Address(null, $matches[1][0], $matches[2][0], $matches[3][0]);

            $lilkes = $restaurantDATA['user_ratings_total'] / (5 / $restaurantDATA['rating']);
            $dislikes = $restaurantDATA['user_ratings_total'] - $lilkes;

            $restaurant = new Restaurant(null, $restaurantDATA['name'], $lilkes, $dislikes);
            $restaurant->setAddress($address);

            $rows[] = [
                $restaurantDATA['name'],
                $restaurantDATA['formatted_address'],
                $restaurant->getLikes(),
                $restaurant->getDislikes(),
            ];

            $this->entityManager->persist($restaurant);

            usleep(9000);
            $bar->advance();
        }

        $bar->finish();
        $bar->clear();

        $io->table(['Name', 'Address', 'Likes', 'Dislikes'], $rows);

        $this->entityManager->flush();

        $io->warning('Address not located in France (NOT IMPORTED) : ' . $badAdrress);
        $io->success('Restaurants imported');

        return Command::SUCCESS;
    }
}
