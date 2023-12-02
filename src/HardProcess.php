<?php

namespace GetMarks;

class HardProcess
{
    private $endpointApi;


    public function __construct($endpoint)
    {
        $this->endpointApi = $endpoint;
    }

    public function getEndpointApi()
    {
        return $this->endpointApi;
    }
    public function setEndpointApi($value)
    {
        $this->endpointApi = $value;
    }


    public static  function getDetails($greeting, $quantity, $product, $multiplier, $receiver)
    {
        echo "Hello " . $greeting . " " .  ($quantity * $multiplier) . " " . $product . " for " . " my " . $receiver;
    }


    public function getObject($response)
    {
        $flights = [];
        if ($this->endpointApi == 'rapidapi') {
            $flights = self::rapidAPI($response);
        } else {
            // ANY OTHER API FOR THE FUTURE
        }


        return $flights;
    }


    private function rapidAPI($response)
    {


        $xml = simplexml_load_string($response);

        $flsResponseFields = [
            'FLSOriginCode' => (string)$xml->FLSResponseFields['FLSOriginCode'],
            'FLSOriginName' => (string)$xml->FLSResponseFields['FLSOriginName'],
            'FLSDestinationCode' => (string)$xml->FLSResponseFields['FLSDestinationCode'],
            'FLSDestinationName' => (string)$xml->FLSResponseFields['FLSDestinationName'],
            'FLSStartDate' => (string)$xml->FLSResponseFields['FLSStartDate'],
            'FLSEndDate' => (string)$xml->FLSResponseFields['FLSEndDate'],
            'FLSResultCount' => (string)$xml->FLSResponseFields['FLSResultCount'],
            'FLSRoutesFound' => (string)$xml->FLSResponseFields['FLSRoutesFound'],
            'FLSBranchCount' => (string)$xml->FLSResponseFields['FLSBranchCount'],
            'FLSTargetCount' => (string)$xml->FLSResponseFields['FLSTargetCount'],
            'FLSRecordCount' => (string)$xml->FLSResponseFields['FLSRecordCount'],
        ];

        $flightDetails = [];
        foreach ($xml->FlightDetails as $details) {
            $flight = [
                'TotalFlightTime' => (string)$details['TotalFlightTime'],
                'TotalMiles' => (string)$details['TotalMiles'],
                'TotalTripTime' => (string)$details['TotalTripTime'],
                'FLSDepartureDateTime' => (string)$details['FLSDepartureDateTime'],
                'FLSDepartureTimeOffset' => (string)$details['FLSDepartureTimeOffset'],
                'FLSDepartureCode' => (string)$details['FLSDepartureCode'],
                'FLSDepartureName' => (string)$details['FLSDepartureName'],
                'FLSArrivalDateTime' => (string)$details['FLSArrivalDateTime'],
                'FLSArrivalTimeOffset' => (string)$details['FLSArrivalTimeOffset'],
                'FLSArrivalCode' => (string)$details['FLSArrivalCode'],
                'FLSArrivalName' => (string)$details['FLSArrivalName'],
                'FLSFlightType' => (string)$details['FLSFlightType'],
                'FLSFlightLegs' => (string)$details['FLSFlightLegs'],
                'FLSFlightDays' => (string)$details['FLSFlightDays'],
                'FLSDayIndicator' => (string)$details['FLSDayIndicator'],
                'FLSPrice' => number_format(rand(10000, 100000) / 100, 2),
                'FLSCurrency' => 'EUR',
                'FlightLegDetails' => []
            ];

            foreach ($details->FlightLegDetails as $leg) {
                $flight['FlightLegDetails'][] = [
                    'DepartureDateTime' => (string)$leg['DepartureDateTime'],
                    'FLSDepartureTimeOffset' => (string)$leg['FLSDepartureTimeOffset'],
                    'ArrivalDateTime' => (string)$leg['ArrivalDateTime'],
                    'FLSArrivalTimeOffset' => (string)$leg['FLSArrivalTimeOffset'],
                    'FlightNumber' => (string)$leg['FlightNumber'],
                    'JourneyDuration' => (string)$leg['JourneyDuration'],
                    'SequenceNumber' => (string)$leg['SequenceNumber'],
                    'LegDistance' => (string)$leg['LegDistance'],
                    'FLSMeals' => (string)$leg['FLSMeals'],
                    'FLSInflightServices' => (string)$leg['FLSInflightServices'],
                    'FLSUUID' => (string)$leg['FLSUUID'],
                    'DepartureAirport' => [
                        'CodeContext' => (string)$leg->DepartureAirport['CodeContext'],
                        'LocationCode' => (string)$leg->DepartureAirport['LocationCode'],
                        'FLSLocationName' => (string)$leg->DepartureAirport['FLSLocationName'],
                        'Terminal' => (string)$leg->DepartureAirport['Terminal'],
                        'FLSDayIndicator' => (string)$leg->DepartureAirport['FLSDayIndicator'],
                    ],
                    'ArrivalAirport' => [
                        'CodeContext' => (string)$leg->ArrivalAirport['CodeContext'],
                        'LocationCode' => (string)$leg->ArrivalAirport['LocationCode'],
                        'FLSLocationName' => (string)$leg->ArrivalAirport['FLSLocationName'],
                        'Terminal' => (string)$leg->ArrivalAirport['Terminal'],
                        'FLSDayIndicator' => (string)$leg->ArrivalAirport['FLSDayIndicator'],
                    ],
                    'MarketingAirline' => [
                        'Code' => (string)$leg->MarketingAirline['Code'],
                        'CodeContext' => (string)$leg->MarketingAirline['CodeContext'],
                        'CompanyShortName' => (string)$leg->MarketingAirline['CompanyShortName'],
                    ],
                    'Equipment' => [
                        'AirEquipType' => (string)$leg->Equipment['AirEquipType'],
                    ],
                ];
            }

            $flightDetails[] = $flight;
        }



        $flights = [
            'FLSResponseFields' => $flsResponseFields,
            'FlightDetails' => $flightDetails,
        ];
        return $flights;
    }
}

class ApiCall
{
    private $origin;
    private $destination;
    private $departureDate;
    private $arrivalDate;
    private $key;
    private $host;
    private $endpointApi;



    public function __construct($key, $host, $endpointApi)
    {
        $this->key = $key;
        $this->host = $host;
        $this->endpointApi = $endpointApi;
    }

    // Getter for $origin
    public function getOrigin()
    {
        return $this->origin;
    }

    // Setter for $origin
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    // Getter for $destination
    public function getDestination()
    {
        return $this->destination;
    }

    // Setter for $destination
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    // Getter for $departureDate
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    // Setter for $departureDate
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;
    }

    // Getter for $arrivalDate
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    // Setter for $arrivalDate
    public function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;
    }



    public function executeApi()
    {
        //$host = $this->host;
        //$origin = $this->origin;
        //$destination = $this->destination;
        //$departureDate = $this->departureDate;
        //$url = "https://$host/TimeTable/$origin/$destination/$departureDate/";
        if ($this->endpointApi == 'rapidapi') {
            $url = "https://{$this->host}/TimeTable/{$this->origin}/{$this->destination}/{$this->departureDate}/";
            $headers = [
                'X-RapidAPI-Key: ' . $this->key,
                'X-RapidAPI-Host: ' . $this->host,
            ];
        } else {
            // ANY OTHER API FOR THE FUTURE
        }


        // Initialize cURL session
        $curl = curl_init();

        // Set cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        return curl_exec($curl);
    }
}
