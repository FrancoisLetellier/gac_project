<?php

namespace App\Controller;

use App\Entity\Data;
use App\Form\ImportType;
use App\Repository\CustomersCallRepository;
use App\Repository\SmsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CallTicketsController extends AbstractController
{
    /**
     * Permet d'importer les data qui nous intéressse pour effectuer les trois requete.
     * il trie les appels, la data et les sms consommé par les abonnés
     * @Route("/", name="call_tickets")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \League\Csv\Exception
     * @throws \League\Csv\InvalidArgument
     * @throws \Doctrine\DBAL\Exception
     */
    public function importAndAnalyseTickets(Request $request,
                                            Filesystem $filesystem,
                                            EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form['import']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $date = new \DateTime('now');
            $fileName = $date->format('Y-m-d') . '-' . uniqid() . '-' . $uploadedFile->getClientOriginalName();
            $path = $destination . '/' . $fileName;
            $uploadedFile->move($destination, $fileName);

            $csv = Reader::createFromPath($path, 'r');
            $csv->setDelimiter(';');

            $csv->setHeaderOffset(2);

            $records = Statement::create()->process($csv, ['account_invoice', 'n_invoice', 'customer', 'date', 'hour', 'real_duration', 'charge_duration', 'type' ]);
            $connection = $em->getConnection();

            foreach ($records as $record) {
                if ($record['type'] === 'envoi de sms depuis le mobile') {
                    $customer = $record['customer'];
                $sql = 'INSERT INTO sms (customer) VALUES (' . $customer . ')';
                $stmt = $connection->prepare($sql);
                $stmt->executeQuery();
                }

                if(strpos($record['type'], 'connexion') !== false) {
                    if ($record['hour'] ==! '#error') {
                        $sql = 'INSERT INTO data_connection (time_connection , data_charge, subscriber) VALUES ("' . $record['hour'] . '",' . $record['charge_duration'] . ',' . $record['customer'] . ')';
                        $stmt = $connection->prepare($sql);
                        $stmt->executeQuery();
                    }
                }

                if(strpos($record['type'], 'appel vers') !== false) {
                        $date = explode('/', $record['date']);
                        $final_date = $date[2] . $date[1] . $date[0];
                        $sql = 'INSERT INTO customers_call ( call_date, real_duration) VALUES ("' . $final_date . '", "' . $record['real_duration'] . '")';
                        $stmt = $connection->prepare($sql);
                        $stmt->executeQuery();
                }
                $filesystem->remove($path);
            }
            $em->flush();
            $filesystem->remove($path);
        }

        return $this->render('call_tickets/import_analyse.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Action de suppression de toutes les données en base de données
     * @Route("/drop_database", name="drop_database")
     * @param EntityManagerInterface $em
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function dropDatabase(EntityManagerInterface $em) {
        $connection = $em->getConnection();
        $sql = 'TRUNCATE TABLE `sms`; TRUNCATE TABLE `data_connection`; TRUNCATE TABLE `customers_call`;';
        $stmt = $connection->prepare($sql);
        $stmt->executeQuery();

        return $this->redirectToRoute('call_tickets');
    }

    /**
     * Renvoi le nombre total de sms envoyé par l'ensemble des abonnés.
     * @Route("/result_sms", name="result_sms")
     * @param SmsRepository $smsRepository
     */
    public function smsCustomersCount(SmsRepository $smsRepository) {
        $result = count($smsRepository->findAll());

        return $this->render('call_tickets/sms_result.html.twig', [
            'result' => $result
        ]);
    }

    /**
     * @Route("/result_call", name="result_calls")
     * @param CustomersCallRepository $customersCallRepository
     * @return Response
     */
    public function durationTotalReal(CustomersCallRepository $customersCallRepository) {
        $calls = $customersCallRepository->callDurationReal();

        $start = new DateTime("1970-01-01 00:00:00");
        $date = new DateTime("1970-01-01 00:00:00");

        $i = 0;
        foreach($calls as $call) {
            $diff = $start->diff($customersCallRepository->callDurationReal()[$i++]->getRealDuration());
            $date->add($diff);

            $result = $start->diff($date);
        }

        dump($result);
        return $this->render('call_tickets/call_duration.html.twig', [
//            'result' => $result;
        ]);
    }
}
