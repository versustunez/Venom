<?php declare(strict_types=1);
use Venom\Entities;
use PHPUnit\Framework\TestCase;

final class EntityTest extends TestCase
{
    public function testAddDBObjectAttr(): void
    {
        $dbObject = new Venom\Entities\DatabaseObject();
        $dbObject->lang = "en";
        $this->assertSame($dbObject->lang, "en");
    }

    public function testSerializeDBObject(): void {
        $dbObject = new Venom\Entities\DatabaseObject();
        $dbObject->lang = "en";
        $this->assertSame(json_encode($dbObject), json_encode(array("lang" => "en")));
    }
}