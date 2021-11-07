<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Search;
use App\Form\InvalidCsvType;
use App\Form\MergeCsvType;
use App\Form\EtlCsvType;
use App\Form\SearchType;
use App\Repository\CustomerRepository;
use App\Repository\MarkRepository;
use App\Service\Csv;
use App\Service\Etl;
use App\Service\Merge;
use App\Service\Invalid;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 */
#[Route('/', name: 'admin_')]
class AdminController extends AbstractController
{
//   //  #[Route('/', name: 'admin_index', methods: ['GET'])]
  private $entityManager;

  private $customerRepository;

  private $markRepository;

  public function __construct(EntityManagerInterface $entityManager, CustomerRepository $customerRepository, MarkRepository $markRepository){
      $this->entityManager = $entityManager;
      $this->customerRepository = $customerRepository;
      $this->markRepository = $markRepository;
  }

  #[Route('/merge', name: 'merge')]
  public function merge(Request $request)
  {
      $redirectTothisRoute = $this->redirectToRoute("admin_merge");

      $csvMergeForm = $this->createForm(MergeCsvType::class);
      $csvMergeForm->handleRequest($request);

      if ($csvMergeForm->isSubmitted() && $csvMergeForm->isValid()) {

          $csv1Descriptor = $csvMergeForm['csv1']->getData();
          $csv2Descriptor = $csvMergeForm['csv2']->getData();
          $mergeCsvName = $csvMergeForm['mergeCsvName']->getData();

          if (!Csv::isValidCsvHeader(Csv::getCsvHeader($csv1Descriptor))){
              $this->addFlash("error","The csv 1 does not contain all columns needed to perform the merge !");
              return $redirectTothisRoute;
          }

          if (!Csv::isValidCsvHeader(Csv::getCsvHeader($csv2Descriptor))){
              $this->addFlash("error","The csv 2 does not contain all columns needed to perform the merge !");
              return $redirectTothisRoute;
          }

          $merge = new Merge($csv1Descriptor, $csv2Descriptor, $mergeCsvName);

          $mergeNotBeenPerfomerdMsg = "The merge has not been performed, either csv's are empty or customers data are invalid !";

          if ($csvMergeForm["type"]->getData() == "Sequential") {
              $mergeSequential = $merge->sequential();
              if ($mergeSequential){
                  $merge->downloadCsv();
              }else{
                  $this->addFlash("error", $mergeNotBeenPerfomerdMsg);
                  return $redirectTothisRoute;
              }
          }

          if ($csvMergeForm["type"]->getData() == "Interlaced") {
              $mergeInterlaced = $merge->interlaced();
              if ($mergeInterlaced){
                  $merge->downloadCsv();
              }else{
                  $this->addFlash("error", $mergeNotBeenPerfomerdMsg);
                  return $redirectTothisRoute;
              }
          }

          return new Response();
      }

      return $this->render('admin/merge.html.twig', [
          "csvMergeForm" => $csvMergeForm->createView()
      ]);
  }

  /**
   * @throws \League\Csv\Exception
   */
  #[Route('add/customers', name: 'add_customers')]
  public function addCustomers(Request $request)
  {
      $csvForm =  $this->createForm(EtlCsvType::class);
      $csvForm->handleRequest($request);

      if ($csvForm->isSubmitted() && $csvForm->isValid()){
          $csv = Reader::createFromPath($csvForm["csv"]->getData())->setHeaderOffset(0);

          $etl = Etl::etl($csv, $this->entityManager, $this->customerRepository, $this->markRepository);

          if($etl["isValidColumns"] === 0) { $this->addFlash("error", "The csv does not contain all columns necessary for insertion in base !"); }
          if($etl["customerExist"] === 0) { $this->addFlash("error", "One or more customer(s) had already been added !"); }
          if($etl["isMajor"] === 0){ $this->addFlash("error", "Some customers were not of legal age, so they were not registered in the database !"); }
          if($etl["isValidSize"] === 0){ $this->addFlash("error", "The size in inches and centimeters of some customers did not match, they were not recorded in the database !"); }
          if($etl["isValidBankIdentidiers"] === 0) { $this->addFlash("error", "Some customers have the same bank identifiers, they have not been registered in the database !"); }
          if($etl["added"] === 0) { $this->addFlash("error", "No customers have been added !"); }
          if($etl["added"] === 1) {
              $this->entityManager->flush();
              $this->addFlash("success", "Valid users have been successfully added !");
              return $this->redirectToRoute("admin_show_customers");
          }

          return $this->redirectToRoute("admin_add_customers");
      }

      return $this->render('admin/add_customers.html.twig', [
          "current_page" => "admin_add_customers",
          "customers" => $this->customerRepository->findAll(),
          "csvForm" => $csvForm->createView()
      ]);
  }

  #[Route('show/customers', name: 'show_customers')]
  public function showCustomers(Request $request)
  {
      $searchForm = $this->createForm(SearchType::class);
      $searchForm->handleRequest($request);

      $customers = $this->customerRepository->findCustomersOrderByVehicleMark($searchForm["mark"]->getData());

      return $this->render('admin/show_customers.html.twig', [
          "current_page" => "admin_show_customers",
          "searchForm" => $searchForm->createView(),
          "headers" => Csv::getSpecificColumns(),
          "customers" => $customers,
          "marks" => $this->markRepository->findAll()
      ]);
  }

  #[Route('invalid/customers', name: 'invalid_customers')]
  public function invalidCustomers(Request $request){
    $redirectTothisRoute = $this->redirectToRoute("admin_invalid_customers");

    $csvForm =  $this->createForm(InvalidCsvType::class);
    $csvForm->handleRequest($request);

    if ($csvForm->isSubmitted() && $csvForm->isValid()) {
        $invalid = $csvForm['invalid']->getData();
        $csvDescriptor = $csvForm['csv']->getData();

        if (!Csv::isValidCsvHeader(Reader::createFromPath($csvDescriptor)->setHeaderOffset(0)->getHeader())){
            $this->addFlash("error", "The csv does not contain all the columns needed to perform the merge !");
            $this->redirectToRoute("admin_invalid_customers");
        }

        $invalidCutomers = new Invalid($csvDescriptor);

        $notBeenPerfomerdMsg = "Either csv is empty, or customers data are all valid !";

        if ($invalid == "notMajor"){
            $notMajorCustomers = $invalidCutomers->notMajor();
            if ($notMajorCustomers){
                $invalidCutomers->downloadCsv("not-major-customers.csv");
            }else{
                $this->addFlash("error", $notBeenPerfomerdMsg);
                return $redirectTothisRoute;
            }
        }

        if ($invalid == "invalidSize"){
            $cutomersWithInvalidSize = $invalidCutomers->invalidSize();
            if ($cutomersWithInvalidSize){
                $invalidCutomers->downloadCsv("customers-with-invalid-size.csv");
            }else{
                $this->addFlash("error", $notBeenPerfomerdMsg);
                return $redirectTothisRoute;
            }
        }

        if ($invalid == "invalidCcNumber") {
            $cutomersWithInvalidCcNbr = $invalidCutomers->invalidCcNumber();
            if ($cutomersWithInvalidCcNbr){
                $invalidCutomers->downloadCsv("customers-with-invalid-cc-number.csv");
            }else{
                $this->addFlash("error", $notBeenPerfomerdMsg);
                return $redirectTothisRoute;
            }
        }

        return new Response();
    }

    return $this->render('admin/invalid_customers.html.twig', [
        "csvForm" => $csvForm->createView()
    ]);
}
}
