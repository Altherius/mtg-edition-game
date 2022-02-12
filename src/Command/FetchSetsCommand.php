<?php

namespace App\Command;

use App\Entity\Set;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch:sets',
    description: 'Gets and updates the sets from Scryfall API',
)]
class FetchSetsCommand extends Command
{
    public function __construct(private EntityManagerInterface $manager, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {


        $io = new SymfonyStyle($input, $output);
        $io->info('Starting set fetching.');

        $guzzle = new Client(['base_uri' => 'https://api.scryfall.com']);

        $io->info('Getting sets from Scryfall...');
        try {
            $response = $guzzle->request('GET', '/sets', ['headers' => ['Accept' => 'application/json']]);
        } catch (GuzzleException $e) {
            $io->error('Error while querying Scryfall.');
            return 1;
        }

        $json = $response->getBody()->getContents();


        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            $sets = $data['data'];
        } catch (JsonException $e) {
            $io->error('Erreur while converting data.');
            return 1;
        }

        $sets = array_filter(
            $sets,
            static function($e) {
                return (
                    !in_array($e['set_type'], ['alchemy', 'treasure_chest', 'memorabilia', 'token', 'promo', 'vanguard']) &&
                    !($e['digital'])
                );
            }
        );

        $io->progressStart(count($sets));

        foreach ($sets as $set) {

            $edition = (new Set())
                ->setName($set['name'])
                ->setScryfallUuid($set['id']);

            if (!$this->manager->getRepository(Set::class)->findOneBy(['scryfallUuid' => $edition->getScryfallUuid()])) {
                $this->manager->persist($edition);
            }

            $io->progressAdvance();
        }

        $io->progressFinish();

        $this->manager->flush();

        $io->success('Fetching ended.');

        return Command::SUCCESS;
    }
}