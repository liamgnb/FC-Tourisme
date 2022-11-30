<?php

namespace App\Command;

use App\Entity\Ville;
use App\Repository\VilleRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Helper\ProgressBar;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:import-villes-franche-comte')]
class ImportVilleFrancheComte extends Command
{
    protected static $defaultDescription = 'Importation des villes de franche comte.';
    private VilleRepository $villeRepository;

    /**
     * @param VilleRepository $villeRepository
     */
    public function __construct(VilleRepository $villeRepository)
    {
        $this->villeRepository = $villeRepository;
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO : vidé avant la table.

        // Récupération du fichier CSV
        $reader = Reader::createFromPath('./src/csv/villes.csv', 'r');
        $reader->setHeaderOffset(0);
        $reader->setDelimiter(';');
        $records = $reader->getRecords();

        // progress bar
        $progressBar = new ProgressBar($output, count($reader));
        $progressBar->start();

        foreach ($records as $offset => $record) {
            if (in_array($record['Département'], [25, 39, 70, 90])) {
                $ville = new Ville();
                $ville
                    ->setCp($record['Code postal'])
                    ->setNumDepartement($record['Département'])
                    ->setLibDepartement($record['Nom département'])
                    ->setLibRegion($record['Région']);

                // Vérification si la ville possède le nom d'une autre, pour éviter les doublons
                if($record['Ancienne commune']){
                    $ville->setNom($record['Commune']." - ".$record['Ancienne commune']);
                } else {
                    $ville->setNom($record['Commune']);
                }
                $this->villeRepository->save($ville, true);
            }
            $progressBar->advance();
        }

        // Affichage Progress Bar et message de fin
        $progressBar->finish();
        $output->writeln('');
        $output->writeln('<info>Villes ajouté avec succes.</info>');
        return Command::SUCCESS;
    }
}