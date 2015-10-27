<?php
  require 'vendor/autoload.php';

  use \DTS\eBaySDK\Constants;
  use \DTS\eBaySDK\Finding\Services;
  use \DTS\eBaySDK\Finding\Types;
  $config = require 'configuration.php'; // Get configuration.php file

  $service = new Services\FindingService(array(
    'appId' => $config['ebay']['production']['appId'], // Your eBay APP ID
    'apiVersion' => $config['ebay']['findingApiVersion'],
    'globalId' => Constants\GlobalIds::GB
  ));

  $request = new Types\FindItemsByKeywordsRequest();

  $words = isset($_GET['search']) ? $_GET['search'] : "";
  $request->keywords = $words;
  $request->paginationInput = new Types\PaginationInput();

  $entriesPerPage = 15; // Ebay items on the page
  $request->paginationInput->entriesPerPage = $entriesPerPage;

  $pageNum = isset($_GET['page']) ? $_GET['page'] : 1;
  $request->paginationInput->pageNumber = (int)($pageNum);
  $response = $service->findItemsByKeywords($request);

  $responsePages = $response->paginationOutput->totalPages;
  $currentPages = 20; // Total page: get 20. Maximum: 100 pages
  $totalEbayPages = min($currentPages, $responsePages);

  $resultEbay = [];

  if ($pageNum <= $totalEbayPages) {
    if ($response->ack !== 'Success') {
      if (isset($response->errorMessage)) {
        foreach ($response->errorMessage->error as $error) {
          printf("Error: %s\n", $error->message);
        }
      }
    } else {
      foreach ($response->searchResult->item as $key => $item) {
        $resultEbay[$key]['title'] = $item->title;
        $resultEbay[$key]['galleryURL'] = isset($item->galleryURL) ? $item->galleryURL : '../images/iconPlaceholder_96x96.gif';;
        $resultEbay[$key]['viewItemURL'] = $item->viewItemURL;
        $resultEbay[$key]['sellingStatus']['currentPrice']['currencyId'] = $item->sellingStatus->currentPrice->currencyId;
        $resultEbay[$key]['sellingStatus']['currentPrice']['value'] = number_format($item->sellingStatus->currentPrice->value, 2, '.', ',');  
        $resultEbay[$key]['shopName'] = 'at eBay';
      }
    }
  }
?>