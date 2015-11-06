<?php
  require __DIR__ . '/vendor/autoload.php';
  use ApaiIO\ApaiIO;
  use ApaiIO\Configuration\GenericConfiguration;
  use ApaiIO\Operations\Search;
  use ApaiIO\Operations\Lookup;
  use ApaiIO\Operations\BrowseNodeLookup;

  $config = require 'configuration.php';

  $page = !isset($_GET['page']) ? 1 : $_GET['page'];
  $words = isset($_GET['search']) ? $_GET['search'] : "";
  $category = 'All';

  $search = new Search();
  $apaiIo = new ApaiIO();
  $conf = new GenericConfiguration();

  if (($category == 'All' && $page <= 5) || ($category != 'All' && $page <= 10)) {
    $response = getResponse($words, $page, $category, $search, $apaiIo, $conf, $config);

    $resultAmazon = [];
    $totalPages = $response->Items->TotalPages; // total search page
    $totalAmazonResult = isset($response->Items->TotalResults) ? $response->Items->TotalResults : 0;

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
        if (isset($itemValue->OfferSummary) || isset($itemValue->ItemAttributes->ListPrice) || isset($itemValue->Offers->Offer->OfferAttributes)) {
          //Image Title
          $resultAmazon[$itemKey]['title'] = $itemValue->ItemAttributes->Title;
          //Product URL
          $resultAmazon[$itemKey]['viewItemURL'] = $itemValue->DetailPageURL;
          //Image URL
          if (isset($itemValue->MediumImage->URL)) {
            $resultAmazon[$itemKey]['galleryURL'] = $itemValue->MediumImage->URL; 
          } else {
            $resultAmazon[$itemKey]['galleryURL'] = '../images/iconPlaceholder_96x96.gif';
          }
          //List Price
          if (isset($itemValue->ItemAttributes->ListPrice)) {
            $listPrice = $itemValue->ItemAttributes->ListPrice->Amount;
            $resultAmazon[$itemKey]['priceId'] = isset($itemValue->ItemAttributes->ListPrice->CurrencyCode) ? $itemValue->ItemAttributes->ListPrice->CurrencyCode : 'USD';
          }
          if (isset($itemValue->OfferSummary)) {
            //New Lowest Price
            if (isset($itemValue->OfferSummary->LowestNewPrice)) {
              $newPrice = $itemValue->OfferSummary->LowestNewPrice->Amount;
              $resultAmazon[$itemKey]['priceId'] = isset($itemValue->OfferSummary->LowestNewPrice->CurrencyCode) ? $itemValue->OfferSummary->LowestNewPrice->CurrencyCode : 'USD';
            }
            //Old Usedd Price
            if (isset($itemValue->OfferSummary->LowestUsedPrice)) { 
              $usedPrice = $itemValue->OfferSummary->LowestUsedPrice->Amount;
              $resultAmazon[$itemKey]['priceId'] = isset($itemValue->OfferSummary->LowestUsedPrice->CurrencyCode) ? $itemValue->OfferSummary->LowestUsedPrice->CurrencyCode : 'USD';
            }
          }
          if (!isset($resultAmazon[$itemKey]['priceId'])) $resultAmazon[$itemKey]['priceId'] = 'USD';
          //Get Old Price vs New Price
          if (isset($listPrice) && isset($newPrice) && isset($usedPrice)) {
            if ($usedPrice >= $newPrice) {
              $resultAmazon[$itemKey]['currentPrice'] = $newPrice / 100;
              $resultAmazon[$itemKey]['oldPrice'] = $usedPrice / 100;
            } else {
              $resultAmazon[$itemKey]['currentPrice'] = $usedPrice / 100;
              $resultAmazon[$itemKey]['oldPrice'] = $newPrice / 100;
            }
          }
          if (!isset($listPrice) && isset($newPrice) && isset($usedPrice)) {
            if ($usedPrice >= $newPrice) {
              $resultAmazon[$itemKey]['currentPrice'] = $newPrice / 100;
              $resultAmazon[$itemKey]['oldPrice'] = $usedPrice / 100;
            } else {
              $resultAmazon[$itemKey]['currentPrice'] = $usedPrice / 100;
              $resultAmazon[$itemKey]['oldPrice'] = $newPrice / 100;
            } 
          }
          if (isset($listPrice) && !isset($newPrice) && isset($usedPrice)) {
            if ($listPrice >= $usedPrice) {
              $resultAmazon[$itemKey]['currentPrice'] = $usedPrice / 100;
              $resultAmazon[$itemKey]['oldPrice'] = $listPrice / 100;
            } else {
              $resultAmazon[$itemKey]['currentPrice'] = $listPPrice / 100;
              $resultAmazon[$itemKey]['oldPrice'] = $usedPrice / 100;
            } 
          }
          if (isset($listPrice) && isset($newPrice) && !isset($usedPrice)) {
            if ($listPrice >= $newPrice) {
              $resultAmazon[$itemKey]['currentPrice'] = $newPrice / 100;
              $resultAmazon[$itemKey]['oldPrice'] = $listPrice / 100;
            } else {
              $resultAmazon[$itemKey]['currentPrice'] = $listPrice / 100;
              $resultAmazon[$itemKey]['oldPrice'] = $newPrice / 100;
            } 
          }
          if (isset($listPrice) && !isset($newPrice) && !isset($usedPrice)) {
            $resultAmazon[$itemKey]['currentPrice'] = $listPrice / 100; 
          }
          if (!isset($listPrice) && !isset($newPrice) && isset($usedPrice)) {
            $resultAmazon[$itemKey]['currentPrice'] = $usedPrice / 100; 
          }
          if (!isset($listPrice) && isset($newPrice) && !isset($usedPrice)) {
            $resultAmazon[$itemKey]['currentPrice'] = $newPrice / 100; 
          }
          if (!isset($listPrice) && !isset($newPrice) && !isset($usedPrice)) {
            $resultAmazon[$itemKey]['currentPrice'] = 0; 
          }
          //Product Condition
          $resultAmazon[$itemKey]['condition'] = strtolower(isset($itemValue->Offers->Offer->OfferAttributes->Condition) ? $itemValue->Offers->Offer->OfferAttributes->Condition : 'other');
          //Product Brand
          if (isset($itemValue->ItemAttributes->Brand)) {
            $resultAmazon[$itemKey]['brand'] = $itemValue->ItemAttributes->Brand;
          } else {
            $resultAmazon[$itemKey]['brand'] = 'Miscellenous';
          }
          $resultAmazon[$itemKey]['shipping'] = 'free';
          //Shop name
          $resultAmazon[$itemKey]['shopName'] = 'at Amazon';
        }
      }

      $resultAmazonTopSellers = getTopSeller($response, $apaiIo, $conf); 
    }
  } else {
    $response = getResponse($words, 1, $category, $search, $apaiIo, $conf, $config);
    $resultAmazonTopSellers = getTopSeller($response, $apaiIo, $conf);
  }

  function getResponse($words, $page, $category, $search, $apaiIO, $conf, $config) {
    $search = new Search();
    $search->setCategory($category);
    $search->setKeywords($words);
    $search->setPage($page);
    $search->setResponseGroup(array('Images', 'ItemAttributes', 'Accessories', 'Offers', 'BrowseNodes'));

    $conf
      ->setCountry('co.uk')
      ->setAccessKey($config['amazon']['ACCESS_KEY_ID']) //Your Amazon Access Key Id
      ->setSecretKey($config['amazon']['SECRET_ACCESS_KEY']) //Your Amazon Secret Key
      ->setAssociateTag($config['amazon']['TAG'])
      ->setRequest('\ApaiIO\Request\Soap\Request');
    $response = $apaiIO->runOperation($search, $conf);

    return $response;
  }

  function getTopSeller($response, $apaiIo, $conf) {
    $ASIN = [];
    $nodeId = getNodeId($response);
    $lookup = new BrowseNodeLookup();
    $lookup->setNodeId($nodeId);
    $lookup->setResponseGroup(array('TopSellers'));
    $formattedResponse = $apaiIo->runOperation($lookup, $conf);

    $lookup = new Lookup();
    foreach ($formattedResponse->BrowseNodes->BrowseNode->TopSellers->TopSeller as $key => $value) {
      $ASIN[$key] = $value->ASIN;
    }
    $resultAmazonTopSellers[0] = getSellerDetails($ASIN[0], $lookup, $apaiIo, $conf);
    $resultAmazonTopSellers[1] = getSellerDetails($ASIN[1], $lookup, $apaiIo, $conf);
    $resultAmazonTopSellers[2] = getSellerDetails($ASIN[2], $lookup, $apaiIo, $conf);
    $resultAmazonTopSellers[3] = getSellerDetails($ASIN[3], $lookup, $apaiIo, $conf);
    $resultAmazonTopSellers = array_filter($resultAmazonTopSellers, "notEmpty");
    return $resultAmazonTopSellers;
  }

  function notEmpty($var) {
    return (count($var) > 0);
  }

  function getNodeId($response) {
    $resultAmazonBrowseNodes = [];
    if (isset($result->Items->Request->Errors->Error->Message)) {
      return;
    } else {
      foreach ($response->Items->Item as $key => $value) {
        if (isset($value->BrowseNodes->BrowseNode->BrowseNodeId)) {
          $resultAmazonBrowseNodes[$key]['nodeId'] = $value->BrowseNodes->BrowseNode->BrowseNodeId;
          $resultAmazonBrowseNodes[$key]['nodeName'] = $value->BrowseNodes->BrowseNode->Name;
        } else {
          if (isset($value->BrowseNodes->BrowseNode[1])) {
            $resultAmazonBrowseNodes[$key]['nodeId'] = $value->BrowseNodes->BrowseNode[1]->BrowseNodeId;
            $resultAmazonBrowseNodes[$key]['nodeName'] = $value->BrowseNodes->BrowseNode[1]->Name;
          }
        }
      }
    }
    return $resultAmazonBrowseNodes[0]['nodeId'];
  }

  function getSellerDetails($asin = '', $lookup, $apaiIO, $conf) {
    $array = [];
    $lookup->setItemId($asin);
    $lookup->setResponseGroup(array('Images', 'ItemAttributes', 'Accessories', 'Offers'));
    $result = $apaiIO->runOperation($lookup, $conf);

    if (isset($result->Items->Request->Errors->Error->Message)) {
       return;
    } else {

      $array['title'] = $result->Items->Item->ItemAttributes->Title;
      $array['viewItemURL'] = $result->Items->Item->DetailPageURL;
      if (isset($result->Items->Item->MediumImage->URL)) {
        $array['galleryURL'] = $result->Items->Item->MediumImage->URL; 
      } else {
        $array['galleryURL'] = '../images/iconPlaceholder_96x96.gif';
      }
      if (isset($result->Items->Item->OfferSummary->LowestNewPrice)) {
        $array['currentPrice'] = $result->Items->Item->OfferSummary->LowestNewPrice->Amount;
        $array['priceId'] = $result->Items->Item->OfferSummary->LowestNewPrice->CurrencyCode;
      } else if (isset($result->Items->Item->ItemAttributes->ListPrice->Amount)) {
        $array['currentPrice'] = $result->Items->Item->ItemAttributes->ListPrice->Amount;
        $array['priceId'] = $result->Items->Item->ItemAttributes->ListPrice->CurrencyCode;
      } else if (isset($result->Items->Item->OfferSummary->LowestUsedPrice)) {
        $array['currentPrice'] = $result->Items->Item->OfferSummary->LowestUsedPrice->Amount;
        $array['priceId'] = $result->Items->Item->OfferSummary->LowestUsedPrice->CurrencyCode;
      }
    }
    return $array;
  }

?>
