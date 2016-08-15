<?php

include("loklak.php");

class Testloklak extends \PHPUnit_Framework_TestCase
{
    private $loklak;

    public function setUp() {
        $this->loklak = new Loklak();
    }

    public function testStatus() {
        $result = $this->loklak->status();
        $statusResponse = json_decode($result);
        $statusResponse = $statusResponse->body;
        $statusResponse = json_decode($statusResponse, true);
        $this->assertArrayHasKey('index', $statusResponse);

        $resultProperties = array(
            'messages', 'mps', 'users', 'queries',
            'accounts', 'user', 'followers', 'following'
        );

        foreach($resultProperties as $property)
        {
            $error = "Indexes does not contain " . $property;
            $this->assertArrayHasKey($property, $statusResponse['index'], $error);
        }
    }

    public function testHello() {
        $result = $this->loklak->hello();
        $helloResponse = json_decode($result);
        $helloResponse = $helloResponse->body;
        $helloResponse = json_decode($helloResponse, true);
        $this->assertEquals('ok', $helloResponse['status']);
    }

    public function testGeocode() {
        $result = $this->loklak->geocode('Hyderabad');
        $geocodeResponse = json_decode($result);
        $geocodeResponse = $geocodeResponse->body;
        $geocodeResponse = json_decode($geocodeResponse, true);
        $this->assertArrayHasKey('locations', $geocodeResponse);
        $this->assertArrayHasKey('Hyderabad', $geocodeResponse['locations']);
        $this->assertEquals('IN', $geocodeResponse['locations']['Hyderabad']['country_code']);
        $this->assertInternalType('array', $geocodeResponse['locations']['Hyderabad']['place']);
    }

    public function testPeers() {
        $result = $this->loklak->peers();
        $peersResponse = json_decode($result);
        $peersResponse = $peersResponse->body;
        $peersResponse = json_decode($peersResponse, true);
        $this->assertArrayHasKey('peers', $peersResponse);
        $this->assertInternalType('array', $peersResponse['peers']);
        $this->assertTrue(sizeof($peersResponse['peers']) >= 1);
        $this->assertEquals(sizeof($peersResponse['peers']), $peersResponse['count']);
    }

    public function testPush() {
        $data='{   "statuses": 
            [     
                {       
                    "id_str": "yourmessageid_1234",       
                    "screen_name": "testuser",       
                    "created_at": "2016-07-22T07:53:24.000Z",       
                    "text": "The rain is spain stays always in the plain",       
                    "source_type": "GENERIC",       
                    "place_name": "Georgia, USA",       
                    "location_point": [3.058579854228782,50.63296878274201],       
                    "location_radius": 0,      
                    "user": {         
                        "user_id": "youruserid_5678", 
                        "name": "Mr. Bob"
                    }     
                }   
            ] 
        }';
        $result = $this->loklak->push(json_decode($data));
        $pushResponse = json_decode($result);
        $pushResponse = $pushResponse->body;
        $pushResponse = json_decode($pushResponse, true);
        $this->assertArrayHasKey('status', $pushResponse);
    }

    public function testUser() {
        $result = $this->loklak->user('Khoslasopan', "10", "10");
        $userResponse = json_decode($result);
        $userResponse = $userResponse->body;
        $userResponse = json_decode($userResponse, true);
        $this->assertArrayHasKey('user', $userResponse);
        $this->assertArrayHasKey('name', $userResponse['user']);
        $this->assertArrayHasKey('screen_name', $userResponse['user']);
    }

    public function testSettings() {
        $result = $this->loklak->settings();
        $settingsResponse = json_decode($result, true);
        if(getenv('TRAVIS') == true)
            $this->assertEquals($settingsResponse[0], "This API has access restrictions: only localhost clients are granted.");
        else 
            $this->assertEquals('200', $settingsResponse['status_code']);
    }

    public function testAccount() {
        // Test for screen_name
        $result = $this->loklak->account("test");
        $accountSettings = json_decode($result, true);
        if(getenv('TRAVIS') == true)
            $this->assertEquals($accountSettings[0], "This API has access restrictions: only localhost clients are granted.");
        else 
            $this->assertEquals('200', $settingsResponse['status_code']);

        //Test for updating account info
        $data = '{
            "screen_name":"test",
            "oauth_token":"abc",
            "oauth_token_secret":"def"
        }';
        $result = $this->loklak->account(NULL, "update", json_decode($data));
        $accountSettings = json_decode($result, true);
        if(getenv('TRAVIS') == true)
            $this->assertEquals($accountSettings[0], "This API has access restrictions: only localhost clients are granted.");
        else 
            $this->assertEquals('200', $settingsResponse['status_code']);
    }

    public function testSearch() {
        $result = $this->loklak->search('loklak', "2016-07-01", "2016-08-02", "KhoslaSopan", 10, "cache");
        $searchResponse = json_decode($result);
        $searchResponse = $searchResponse->body;
        $searchResponse = json_decode($searchResponse, true);
        $this->assertArrayHasKey('statuses', $searchResponse);
        $this->assertInternalType('array', $searchResponse['statuses']);
        $this->assertTrue(sizeof($searchResponse['statuses']) >= 1);
        $this->assertEquals(sizeof($searchResponse['statuses']), $searchResponse['search_metadata']['count']);
    }

    public function testSusi() {
        $result = $this->loklak->susi('Hi I am Zeus');
        $susiResponse = json_decode($result);
        $susiResponse = $susiResponse->body;
        $susiResponse = json_decode($susiResponse, true);
        $this->assertTrue(sizeof($susiResponse['answers']) >= 1);
    }

    public function testAggregations() {
        $result = $this->loklak->aggregations("spacex", "2016-04-01", "2016-04-06", array("mentions","hashtags"), 10, 6);
        $aggregationsResponse = json_decode($result);
        $aggregationsResponse = $aggregationsResponse->body;
        $aggregationsResponse = json_decode($aggregationsResponse, true);
        $this->assertArrayHasKey('statuses', $aggregationsResponse);
        $this->assertArrayHasKey('hashtags', $aggregationsResponse['aggregations']);
        $this->assertArrayHasKey('mentions', $aggregationsResponse['aggregations']);
    }

    public function testSuggest() {
        $result = $this->loklak->suggest("spacex", NULL, "asc", "query_count", "2016-07-01", "now");
        $suggestResponse = json_decode($result);
        $suggestResponse = $suggestResponse->body;
        $suggestResponse = json_decode($suggestResponse, true);
        $this->assertArrayHasKey('queries', $suggestResponse);
        $this->assertTrue(sizeof($suggestResponse['queries']) >= 1);
        $this->assertEquals(sizeof($suggestResponse['queries']), $suggestResponse['search_metadata']['count']);
    }

    public function testMarkdown() {
        $result = $this->loklak->markdown("hello world", "000000", "ffffff", "2");
        $markdownResponse = $result;
        $this->assertNotEquals($markdownResponse, "");
    }

    public function testMap() {
        $result = $this->loklak->map("Hello World", "29.157176", "48.125024");
        $mapResponse = $result;
        $this->assertNotEquals($mapResponse, "");
    }
}
