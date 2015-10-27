<?php
  require __DIR__ . '/vendor/autoload.php';
  use ApaiIO\ApaiIO;
  use ApaiIO\Configuration\GenericConfiguration;
  use ApaiIO\Operations\Search;
  $config = require 'configuration.php';

  $page = !isset($_GET['page']) ? 1 : $_GET['page'];
  $words = isset($_GET['search']) ? $_GET['search'] : "";
  $category = 'All';

  if (($category == 'All' && $page <= 5) || ($category != 'All' && $page <= 10)) {
    $search = new Search();
    $search->setCategory($category);
    $search->setKeywords($words);
    $search->setPage($page);
    $search->setResponseGroup(array('Images', 'ItemAttributes', 'Accessories', 'Offers'));

    $apaiIo = new ApaiIO();
    $conf = new GenericConfiguration();
    $conf
      ->setCountry('co.uk')
      ->setAccessKey($config['amazon']['ACCESS_KEY_ID']) //Your Amazon Access Key Id
      ->setSecretKey($config['amazon']['SECRET_ACCESS_KEY']) //Your Amazon Secret Key
      ->setAssociateTag($config['amazon']['TAG'])
      ->setRequest('\ApaiIO\Request\Soap\Request');

    $response = $apaiIo->runOperation($search, $conf);
    $resultAmazon = [];
    $totalPages = $response->Items->TotalPages; // total search page

    $maxAllPages = 5; // Max pages for All category
    $maxOtherPages = 10; // Max pages for other category
    $resultAmazonPages = 5; // Variable to set pages

    if ($category == 'All') {
      $totalAmazonPages = min($maxAllPages, $totalPages);
    } else {
      $totalAmazonPages = min($maxOtherPages, $totalPages);
    }

    if (empty($response->Items->Request->Errors->Error->Message)) {
      foreach ($response->Items->Item as $itemKey => $itemValue) {
        if (isset($itemValue->OfferSummary) || isset($itemValue->ItemAttributes->ListPrice)) {
          $resultAmazon[$itemKey]['ASIN'] = $itemValue->ASIN;
          $resultAmazon[$itemKey]['DetailPageURL'] = $itemValue->DetailPageURL;
          $resultAmazon[$itemKey]['ItemAttributes']['Title'] = $itemValue->ItemAttributes->Title;
          if (isset($itemValue->MediumImage->URL)) {
            $resultAmazon[$itemKey]['MediumImage'] = $itemValue->MediumImage->URL; 
          } else {
            $resultAmazon[$itemKey]['MediumImage'] = '../images/iconPlaceholder_96x96.gif';
          }
          if (isset($itemValue->ItemAttributes->ListPrice)) {
            $resultAmazon[$itemKey]['ItemAttributes']['ListPrice']['Amount'] = $itemValue->ItemAttributes->ListPrice->Amount;
            $resultAmazon[$itemKey]['ItemAttributes']['ListPrice']['CurrencyCode'] = $itemValue->ItemAttributes->ListPrice->CurrencyCode;
            $resultAmazon[$itemKey]['ItemAttributes']['ListPrice']['FormattedPrice'] = $itemValue->ItemAttributes->ListPrice->FormattedPrice;
          }
          if (isset($itemValue->OfferSummary)) {
            if (isset($itemValue->OfferSummary->LowestNewPrice)) {
              $resultAmazon[$itemKey]['OfferSummary']['LowestNewPrice']['Amount'] = $itemValue->OfferSummary->LowestNewPrice->Amount;
              $resultAmazon[$itemKey]['OfferSummary']['LowestNewPrice']['CurrencyCode'] = $itemValue->OfferSummary->LowestNewPrice->CurrencyCode;
              $resultAmazon[$itemKey]['OfferSummary']['LowestNewPrice']['FormattedPrice'] = $itemValue->OfferSummary->LowestNewPrice->FormattedPrice;
            }
            if (isset($itemValue->OfferSummary->LowestUsedPrice)) {
              $resultAmazon[$itemKey]['OfferSummary']['LowestUsedPrice']['Amount'] = $itemValue->OfferSummary->LowestUsedPrice->Amount;
              $resultAmazon[$itemKey]['OfferSummary']['LowestUsedPrice']['CurrencyCode'] = $itemValue->OfferSummary->LowestUsedPrice->CurrencyCode;
              $resultAmazon[$itemKey]['OfferSummary']['LowestUsedPrice']['FormattedPrice'] = $itemValue->OfferSummary->LowestUsedPrice->FormattedPrice;
            }
          }
          $resultAmazon[$itemKey]['shopName'] = 'at Amazon';
        }
      }
    }
  }
?>
