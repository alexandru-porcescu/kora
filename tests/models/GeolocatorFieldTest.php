<?php
use App\GeolocatorField;
use App\Field;
use Illuminate\Support\Facades\DB;

/**
 * Class GeolocatorFieldTest
 * @group field
 */
class GeolocatorFieldTest extends TestCase
{
    /**
     * Constants to save some space.
     */
    const LOCATIONS = <<<TEXT
[Desc]Hey There! (mřímí) a běš nitlí? Fréř&ňoni $3500 zkedě||z tini nitrudr sepodi o báfé pěkmě?[Desc][LatLon]40.7591126,-73.979531081419[LatLon][UTM]18T:586135.37683858,4512517.6620784[UTM][Address]30 Rockefeller Plaza New York[Address][!][Desc]Lorem ipsum dolor sit amet, consectetur adipiscing elit.[Desc][LatLon]42.733398,-84.468676[LatLon][UTM]16T:707219.27661307,4734317.0614896[UTM][Address]201 Milford East Lansing[Address][!][Desc]Hey! :) [Desc][LatLon]null,null[LatLon][UTM]null:null.null[UTM][Address]15029 161st ave Grand Haven [Address]
TEXT;
    const FIELD_DEFAULT = <<<TEXT
[Desc]Default location desc[Desc][LatLon]40.449938,-86.1288379[LatLon][UTM]16T:573872.22456101,4478062.2545318[UTM][Address]1 Easy Street[Address]
TEXT;
    const FIELD_OPTIONS = <<<TEXT
[!Map!]No[!Map!][!DataView!]LatLon[!DataView!]
TEXT;

    /**
     * Test the keyword search function.
     * @group search
     */
    public function test_keywordSearch() {
        $project = self::dummyProject();
        $this->assertInstanceOf('App\Project', $project);

        $form = self::dummyForm($project->pid);
        $this->assertInstanceOf('App\Form', $form);

        $field = self::dummyField("Geolocator", $project->pid, $form->fid);
        $this->assertInstanceOf('App\Field', $field);

        $record = self::dummyRecord($project->pid, $form->fid);
        $this->assertInstanceOf('App\Record', $record);

        $field->default = self::FIELD_DEFAULT;
        $field->options = self::FIELD_OPTIONS;
        $field->save();

        $geo = new \App\GeolocatorField();
        $geo->rid = $record->rid;
        $geo->flid = $field->flid;
        $geo->locations = self::LOCATIONS;
        $geo->save();

        $args = ['hey there!'];
        $this->assertTrue($geo->keywordSearch($args, true));
        $this->assertTrue($geo->keywordSearch($args, false));

        // Test search on default values (not in the location string of the actual geolocator field).
        $args = ['default location desc'];
        $this->assertTrue($geo->keywordSearch($args, true));
        $this->assertTrue($geo->keywordSearch($args, false));

        $args = ['easy', 'street'];
        $this->assertTrue($geo->keywordSearch($args, true));
        $this->assertTrue($geo->keywordSearch($args, false));

        $args = ['ocation']; // Partial
        $this->assertTrue($geo->keywordSearch($args, true));
        $this->assertFalse($geo->keywordSearch($args, false));

        // Test values in the actual geolocator's locations.

        $args = ['&ňoni $3500 zkedě||z', 'ere'];
        $this->assertTrue($geo->keywordSearch($args, true));
        $this->assertFalse($geo->keywordSearch($args, false));

        $args = ['$3500', 'Fréř&ňoni', '(mřímí)', 'zkedě||z']; // Try to break the regex.
        $this->assertTrue($geo->keywordSearch($args, true));
        $this->assertTrue($geo->keywordSearch($args, false));

        $args = ['201 Milford'];
        $this->assertTrue($geo->keywordSearch($args, true));
        $this->assertTrue($geo->keywordSearch($args, false));

        $args = ['15029 161st'];
        $this->assertTrue($geo->keywordSearch($args, true));
        $this->assertTrue($geo->keywordSearch($args, false));

        $args = [1234, -1, null];
        $this->assertFalse($geo->keywordSearch($args, true));
        $this->assertFalse($geo->keywordSearch($args, false));
    }

    /**
     * Test the get locations function.
     */
    public function test_getLocations() {
        $project = self::dummyProject();
        $this->assertInstanceOf('App\Project', $project);

        $form = self::dummyForm($project->pid);
        $this->assertInstanceOf('App\Form', $form);

        $field = self::dummyField("Geolocator", $project->pid, $form->fid);
        $this->assertInstanceOf('App\Field', $field);

        $record = self::dummyRecord($project->pid, $form->fid);
        $this->assertInstanceOf('App\Record', $record);

        $field->default = self::FIELD_DEFAULT;
        $field->options = self::FIELD_OPTIONS;
        $field->save();

        $geo = new \App\GeolocatorField();
        $geo->rid = $record->rid;
        $geo->flid = $field->flid;
        $geo->locations = self::LOCATIONS;
        $geo->save();

        foreach($geo->getLocations() as $location) {
            $this->assertTrue(count($location) == 4);
        }
    }

    /**
     * Test the add locations function.
     */
    public function test_addLocations() {
        $project = self::dummyProject();
        $form = self::dummyForm($project->pid);
        $field = self::dummyField(Field::_GEOLOCATOR, $project->pid, $form->fid);
        $record = self::dummyRecord($project->pid, $form->fid);

        $geo = new GeolocatorField();
        $geo->rid = $record->rid;
        $geo->flid = $field->flid;
        $geo->locations = "";
        $geo->save();

        $geo->addLocations(
            [
                "[Desc]afsdaf[Desc][LatLon]11,11[LatLon][UTM]32P:718529.11253281,1216707.4085526[UTM][Address]  Caloocan [Address]",
                "[Desc]jangus[Desc][LatLon]-11.445678,45.12345[LatLon][UTM]38L:513465.49707984,8734738.1327539[UTM][Address]   [Address]"
            ]);

        $this->assertNotEmpty(DB::table('geolocator_support')->select("*")->where("id", ">", 0)->get());

        $location = DB::table('geolocator_support')->select("*")->where("rid", "=", $record->rid)->get()[1];

        $this->assertEquals('jangus', $location->desc);
        $this->assertEquals(-11.445678, $location->lat);
        $this->assertEquals(45.12345, $location->lon);
        $this->assertEquals('38L', $location->zone);
        $this->assertEquals(513465.497, $location->easting, "", 0.01);
        $this->assertEquals(8734738.132, $location->northing, "", 0.01);
        $this->assertEquals("", $location->address);
    }

    /**
     * Test the locations function.
     */
    public function test_locations() {
        $project = self::dummyProject();
        $form = self::dummyForm($project->pid);
        $field = self::dummyField(Field::_GEOLOCATOR, $project->pid, $form->fid);
        $record = self::dummyRecord($project->pid, $form->fid);

        $geo = new GeolocatorField();
        $geo->rid = $record->rid;
        $geo->flid = $field->flid;
        $geo->locations = "";
        $geo->save();

        $geo->addLocations(
            [
                "[Desc]afsdaf[Desc][LatLon]11,11[LatLon][UTM]32P:718529.11253281,1216707.4085526[UTM][Address]  Caloocan [Address]",
                "[Desc]jangus[Desc][LatLon]-11.445678,45.12345[LatLon][UTM]38L:513465.49707984,8734738.1327539[UTM][Address]   [Address]"
            ]);

        $locations = $geo->locations()->get();
        $this->assertNotEmpty($locations);

        $this->assertEquals("afsdaf", $locations[0]->desc);
        $this->assertEquals("jangus", $locations[1]->desc);
    }

    /**
     * Test the UTM to Lat Lon static function.
     */
    public function test_UTMToPoint() {
        $zone = "16T";
        $easting = 706602;
        $northing = 4734069;

        $point = GeolocatorField::UTMToPoint($zone, $easting, $northing);

        $this->assertEquals($point->Lat(), 42.731328, "", 0.1);
        $this->assertEquals($point->Long(), -84.476290, "", 0.1);

        $zone = "33M";
        $easting = 529292.21;
        $northing = 9509196.92;

        $point = GeolocatorField::UTMToPoint($zone, $easting, $northing);

        $this->assertEquals($point->Lat(), -4.440313, "", 0.1);
        $this->assertEquals($point->Long(), 15.264028, "", 0.1);
    }

    /**
     * Test the Address to Lat Lon static function.
     */
    public function test_addressToPoint() {
        $address = "288 Farm Ln #409, East Lansing, MI 48823";

        $point = GeolocatorField::addressToPoint($address);

        $this->assertEquals($point->Lat(), 42.731328, "", 0.1);
        $this->assertEquals($point->Long(), -84.476290, "", 0.1);

        $address = "Buckingham Palace, London SW1A 1AA, UK";

        $point = GeolocatorField::addressToPoint($address);

        $this->assertEquals($point->Lat(), 51.501557, "", 0.1);
        $this->assertEquals($point->Long(), -0.143265, "", 0.1);
    }

    /**
     * Test the get advanced search query function.
     */
    public function test_getAdvancedSearchQuery() {
        $project = self::dummyProject();
        $form = self::dummyForm($project->pid);
        $field = self::dummyField(Field::_GEOLOCATOR, $project->pid, $form->fid);
        $record1 = self::dummyRecord($project->pid, $form->fid);

        $geo1 = new GeolocatorField();
        $geo1->flid = $field->flid;
        $geo1->rid = $record1->rid;
        $geo1->locations = "";
        $geo1->save();

        $geo1->addLocations(["[Desc]Somewhere![Desc][LatLon]41.972359,-87.690095[LatLon][UTM]16T:442823.09811405,4646937.5690537[UTM][Address]5001 North Lincoln Avenue Chicago Illinois[Address]"]);

        $dummy_query = [
            $geo1->flid."_type" => "LatLon",
            $geo1->flid."_lat" => "41.976887",
            $geo1->flid."_lon" => "-87.692084",
            $geo1->flid."_range" => "1"
        ];

        $query = $geo1->getAdvancedSearchQuery($geo1->flid, $dummy_query);

        $rids = $query->get();

        $this->assertEquals($rids[0]->rid, $record1->rid);

        // Try a larger distance.
        $record2 = self::dummyRecord($project->pid, $form->fid);

        $geo2 = new GeolocatorField();
        $geo2->flid = $field->flid;
        $geo2->rid = $record2->rid;
        $geo2->locations = "";
        $geo2->save();

        $geo2->addLocations(["[Desc]Planalto Palace Palácio do Planalto[Desc][LatLon]-15.799167,-47.860833[LatLon][UTM]23L:193501.89147454,8251194.8450127[UTM][Address] Via N1 Leste Brasília Federal District[Address]"]);

        $dummy_query = [
            $geo2->flid."_type" => "LatLon",
            $geo2->flid."_lat" => "-33.432089",
            $geo2->flid."_lon" => "-70.645564",
            $geo2->flid."_range" => "3100"
        ];

        $query = $geo2->getAdvancedSearchQuery($geo2->flid, $dummy_query);

        $rids = $query->get();

        $this->assertEquals($rids[0]->rid, $record2->rid);

        // Ensure that distances outside our curved circle are not returned.
        $dummy_query = [
            $geo1->flid."_type" => "LatLon",
            $geo1->flid."_lat" => "-33.856788",
            $geo1->flid."_lon" => "151.215292",
            $geo1->flid."_range" => "1000"
        ];

        $query = $geo1->getAdvancedSearchQuery($geo1->flid, $dummy_query);

        $this->assertEmpty($query->get());

        //
        // Redo above tests with UTM coordinates.
        //
        $dummy_query = [
            $geo1->flid."_type" => "UTM",
            $geo1->flid."_zone" => "16T",
            $geo1->flid."_east" => "442662.36",
            $geo1->flid."_north" => "4647441.63",
            $geo1->flid."_range" => "1"
        ];

        $query = $geo1->getAdvancedSearchQuery($geo1->flid, $dummy_query);

        $rids = $query->get();

        $this->assertEquals($rids[0]->rid, $record1->rid);

        $dummy_query = [
            $geo2->flid."_type" => "UTM",
            $geo1->flid."_zone" => "19H",
            $geo1->flid."_east" => "347023.59",
            $geo1->flid."_north" => "6299599.45",
            $geo2->flid."_range" => "3100"
        ];

        $query = $geo2->getAdvancedSearchQuery($geo2->flid, $dummy_query);

        $rids = $query->get();

        $this->assertEquals($rids[0]->rid, $record2->rid);

        $dummy_query = [
            $geo1->flid."_type" => "UTM",
            $geo1->flid."_zone" => "56H",
            $geo1->flid."_east" => "334899.80",
            $geo1->flid."_north" => "6252290.07",
            $geo1->flid."_range" => "1000"
        ];

        $query = $geo1->getAdvancedSearchQuery($geo1->flid, $dummy_query);

        $this->assertEmpty($query->get());

        //
        // Redo above tests with Addresses.
        //
        $dummy_query = [
            $geo1->flid."_type" => "Address",
            $geo1->flid."_address" => "5233 North Lincoln Avenue Chicago Illinois",
            $geo1->flid."_range" => "1"
        ];

        $query = $geo1->getAdvancedSearchQuery($geo1->flid, $dummy_query);

        $rids = $query->get();

        $this->assertEquals($rids[0]->rid, $record1->rid);

        $dummy_query = [
            $geo2->flid."_type" => "Address",
            $geo2->flid."_address" => "Bellavista Recoleta Región Metropolitana De Santiago",
            $geo2->flid."_range" => "3100"
        ];

        $query = $geo2->getAdvancedSearchQuery($geo2->flid, $dummy_query);

        $rids = $query->get();

        $this->assertEquals($rids[0]->rid, $record2->rid);

        $dummy_query = [
            $geo1->flid."_type" => "Address",
            $geo1->flid."_address" => "Lower Concourse Sydney New South Wales",
            $geo1->flid."_range" => "1000"
        ];

        $query = $geo1->getAdvancedSearchQuery($geo1->flid, $dummy_query);

        $this->assertEmpty($query->get());
    }
}