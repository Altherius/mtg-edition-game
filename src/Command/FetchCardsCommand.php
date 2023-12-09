<?php

namespace App\Command;

use App\Entity\Card;
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
    name: 'app:fetch:cards',
    description: 'Fetch cards for given editions from Scryfall',
)]
class FetchCardsCommand extends Command
{
    public function __construct(private EntityManagerInterface $manager, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->manager->getConnection()->executeQuery('DELETE FROM card WHERE 1');
        $sets = $this->manager->getRepository(Set::class)->findAll();
        $io = new SymfonyStyle($input, $output);
        $guzzle = new Client(['base_uri' => 'https://api.scryfall.com']);

        $io->info('Getting sets from local database...');
        $sets = $this->manager->getRepository(Set::class)->findAll();
        $io->info('Getting cards from Scryfall...');
        $io->progressStart(count($sets));

        foreach ($sets as $set) {
            try {
                $setResponse = $guzzle->request('GET', '/sets/' . $set->getScryfallUuid(), ['headers' => ['Accept' => 'application/json']]);
                $json = $setResponse->getBody()->getContents();
                $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

                sleep(.2);


                $cardsResponse = $guzzle->request('GET', $data['search_uri']);
                $json = $cardsResponse->getBody()->getContents();
                $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);


                do {
                    $cards = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

                    foreach ($cards['data'] as $card) {
                        $new = (new Card())
                            ->setName($card['name'])
                            ->setImage($card['image_uris']['normal'] ?? $card['card_faces'][0]['image_uris']['normal'])
                            ->setLinkedSet($set)
                        ;

                        $this->manager->persist($new);
                    }

                    sleep(.2);
                    if ($data['has_more']) {
                        $cardsResponse = $guzzle->request('GET', $data['next_page']);
                        $json = $cardsResponse->getBody()->getContents();
                        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
                    }
                } while($cards['has_more']);


            } catch (GuzzleException $e) {
                $io->error('Error while querying Scryfall: ' . $e->getMessage());
            } catch (JsonException $e) {
                $io->error('Error while converting data: ' . $e->getMessage());
            }

            $this->manager->flush();
            $io->progressAdvance();
        }

        $io->progressFinish();
        return Command::SUCCESS;
    }
}
