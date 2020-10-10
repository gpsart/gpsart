<?php

declare(strict_types=1);

namespace App\Command;

use App\Api\Entity\Activity;
use App\Api\Entity\Route;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

class FakeActivityCommand extends Command
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected static $defaultName = 'app:fake-activity';

    protected function configure()
    {
        $this->addArgument('routeId', InputOption::VALUE_REQUIRED, 'Route uuid');
        $this->addOption(
            'count',
            null,
            InputOption::VALUE_OPTIONAL,
            'Number of activities to be generated',
            10
        );
        $this->addOption('estimatedByStravaTime', null, InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $route = $this->entityManager
            ->getRepository(Route::class)
            ->findOneById($input->getArgument('routeId'));

        $estimatedTime = (int)$input->getOption('estimatedByStravaTime');

        if ($estimatedTime === 0) {
            $estimatedTime = random_int(1200, 5400);
        }

        $faker = Factory::create();

        for ($i = 0; $i < (int)$input->getOption('count'); $i++) {
            $this->entityManager->persist(new Activity(
                Uuid::v4()->toRfc4122(),
                $route,
                random_int((int)($estimatedTime * 0.8), (int)($estimatedTime * 1.2)),
                $faker->name,
                ''
            ));
        }

        $this->entityManager->flush();
        return Command::SUCCESS;
    }
}